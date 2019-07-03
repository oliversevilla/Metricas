<?php
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/instancia/moInstancia.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/metadata/moMetadata.php");

//Variables comunes
$url=$_REQUEST['url'];
$tags = get_meta_tags($url);//busca y almacena la estructura Doublin Core
$xml = simplexml_load_file($url);//busca y almacena la estructura LOM

//Creacion de objetos
$oa = new oa(-1);
$meta = new metadata(0,'');

//------------------------------
//          DUBLIN CORE
//------------------------------
if($tags['dc_title']!='') {
    //guardo variables del OA para insertar en la tabla "meoa"
    $titulo=$tags['dc_title'];
    $std='DC';
    $idOA=$oa->insert($titulo,$std,$url);
    if($idOA > 0){
        //creacion de objeto catalogo
        $catalogo = new catalogo(1,'');//1 si es DUBLIN CORE
        $arrCat=$catalogo->arregloCatalogos;
        //traer la metas del DC desde la tabla "catalogo"
        for($i=0;$i<count($arrCat);$i++){
            
            $etiquetaDC=$arrCat[$i]->ca_etiqueta;
            $contentDC=$tags[$etiquetaDC];//obtener el contenido de cada meta de DC, si no existe la meta -> ""
            
            //---------------------------------------------------------------Evaluar formula Completitud
            if(trim($contentDC)!="") {
                $contenidoDC=$contentDC;
                $valCompDC=1; 
            }
            else {
                $contenidoDC="";
                $valCompDC=0;
            }
            $completitudDC=$arrCat[$i]->ca_peso * $valCompDC;//al final en la formula sumar estos valores y actualizar en meoa
            //---------------------------------------------------------------Evaluar formula Consistencia            
            $instancia = new instancia(1,$arrCat[$i]->ca_id,0);//1 si es DUBLIN CORE
            $arrInstancia = $instancia->arregloInstancia;//arreglo de instancias con las que se comparara c/meta (formula)            
            if(count($arrInstancia)>0){//si tiene instancias, evaluar
                $permitido=0;//0 si el meta no esta en valores permitidos
                for($j=0;$j<count($arrInstancia);$j++){
                    if($contentDC==$arrInstancia[$j]->in_nombre) $permitido=1;
                }
            }
            else{
                if($contentDC!="") {
                    $contenidoDC=$contentDC;
                    $permitido=1; 
                }
                else {
                    $contenidoDC="";
                    $permitido=0;
                }                
            }            
            $consistenciaDC=$permitido;//al final en la formula sumar y dividir para el total de metas, y actualizar en meoa
            //--------------------Evaluar formula Coherencia
            //para formula de Ochoa
            if(trim($etiquetaDC=='dc_title')) $titulo=$contentDC;
            if(trim($etiquetaDC=='dc_description')) $descripcion=$contentDC;
            //No existe para los metas analizados del DOM -> 1 para todos (coherente)
            $coherenciaDC=1;
            
            $meta->insert($idOA,$arrCat[$i]->ca_id,-1,$contenidoDC,$etiquetaDC,$completitudDC,$consistenciaDC,$coherenciaDC);
        }
        
        //para formula de Ocohoa
        $coherenciaOchoa = Qcoh($titulo,$descripcion,$coherenciaDC);
        
        //actualizo metrica Coherencia        
        $meta->updateCoherencia($idOA,round($coherenciaOchoa,2)); 
        echo $oa->getMaxId();
    }
    else echo -2;//El OA no se pudo insertar (subir) en el servidor
}
//------------------------------
//              LOM
//------------------------------
elseif($xml->general->title->string!='') {    
    ////foreach ($xml->general->title as $nodo) echo $nodo->string;
    $titulo=$xml->general->title->string;
    $std='LOM';
    
    $idOA=$oa->insert($titulo,$std,$url);    
    
    if($idOA > 0){
        //creacion de objeto catalogo
        $catalogo = new catalogo(2,'');//2 si es LOM
        $arrCat=$catalogo->arregloCatalogos;        
        //traer la metas del LOM desde la tabla "catalogo"
        for($i=0;$i<count($arrCat);$i++){
            
            $etiquetaLOM=$arrCat[$i]->ca_etiqueta;//Ej: title
            $subgrupoLOM=$arrCat[$i]->ca_subgrupo;//Ej: general
            //obtener el contenido de cada meta de LOM
            if(trim($etiquetaLOM)=='identifier' && trim($subgrupoLOM)=='general')
                $contentLOM=$xml->$subgrupoLOM->$etiquetaLOM->entry;
            elseif(trim($etiquetaLOM)=='structure' && trim($subgrupoLOM)=='general')
                $contentLOM=$xml->$subgrupoLOM->$etiquetaLOM->value;
            elseif(trim($etiquetaLOM)=='aggregationLevel' && trim($subgrupoLOM)=='general')
                $contentLOM=$xml->$subgrupoLOM->$etiquetaLOM->value;
            elseif(trim($etiquetaLOM)=='language' && trim($subgrupoLOM)=='general')
                $contentLOM=$xml->$subgrupoLOM->$etiquetaLOM;
            elseif(trim($etiquetaLOM)=='entity' && trim($subgrupoLOM)=='lifeCycle')
                $contentLOM=$xml->$subgrupoLOM->contribute->$etiquetaLOM;
            elseif(trim($etiquetaLOM)=='role' && trim($subgrupoLOM)=='lifeCycle')
                $contentLOM=$xml->$subgrupoLOM->contribute->$etiquetaLOM->value;
            elseif(trim($etiquetaLOM)=='dateTime' && trim($subgrupoLOM)=='lifeCycle')
                $contentLOM=$xml->$subgrupoLOM->contribute->date->$etiquetaLOM;
            elseif(trim($etiquetaLOM)=='status' && trim($subgrupoLOM)=='lifeCycle')
                $contentLOM=$xml->$subgrupoLOM->$etiquetaLOM->value;
            elseif(trim($etiquetaLOM)=='learningResourceType' && trim($subgrupoLOM)=='educational')
                $contentLOM=$xml->$subgrupoLOM->$etiquetaLOM->value;
            else $contentLOM=$xml->$subgrupoLOM->$etiquetaLOM->string;//si no existe la meta -> ""
            //---------------------------------------------------------------Evaluar formula Completitud
            if($contentLOM!="") {
                $contenidoLOM=$contentLOM;
                $valCompLOM=1; 
            }
            else {
                $contenidoLOM="";
                $valCompLOM=0;
            }
            $completitudLOM=$arrCat[$i]->ca_peso * $valCompLOM;//al final en la formula sumar estos valores y actualizar en meoa
            //---------------------------------------------------------------Evaluar formula Consistencia            
            $instancia = new instancia(2,$arrCat[$i]->ca_id,0);//2 si es LOM
            $arrInstancia = $instancia->arregloInstancia;//arreglo de instancias con las que se comparara c/meta (formula)            
            if(count($arrInstancia)>0){//si tiene instancias, evaluar
                $permitido=0;//0 si el meta no esta en valores permitidos
                for($j=0;$j<count($arrInstancia);$j++){
                    if($contentLOM==$arrInstancia[$j]->in_nombre) $permitido=1;
                }
            }
            else{
                if($contentLOM!="") {
                    $contenidoLOM=$contentLOM;
                    $permitido=1; 
                }
                else {
                    $contenidoLOM="";
                    $permitido=0;
                }                
            }            
            $consistenciaLOM=$permitido;//al final en la formula sumar y dividir para el total de metas, y actualizar en meoa
            //--------------------Evaluar formula Coherencia
            if(trim($etiquetaLOM=='structure')) $structure=$contentLOM;
            if(trim($etiquetaLOM=='aggregationLevel')) $aggregationLevel=$contentLOM;
            
            $coherenciaLOM=-1;
            
            $meta->insert($idOA,$arrCat[$i]->ca_id,-1,$contenidoLOM,$etiquetaLOM,$completitudLOM,$consistenciaLOM,$coherenciaLOM);
            
            //para formula de Ochoa
            if(trim($etiquetaLOM=='title')) $titulo=$contentLOM;
            if(trim($etiquetaLOM=='description')) $descripcion=$contentLOM;
            
        }
        //Actualizar valor metrica Coherencia (fuera del FOR xq no se puede calcular dentro de esta iteracion)
        if(trim($structure)=='atomic'){
            if(trim($aggregationLevel)=='1') $coherenciaLOMFinal=1;
            if(trim($aggregationLevel)=='2') $coherenciaLOMFinal=0.5;
            if(trim($aggregationLevel)=='3') $coherenciaLOMFinal=0.25;
            if(trim($aggregationLevel)=='4') $coherenciaLOMFinal=0.125;
        }
        if(trim($structure)=='collection' || trim($structure)=='networked' ||trim($structure)=='hierarchical' || trim($structure)=='linear'){
            if(trim($aggregationLevel)=='1') $coherenciaLOMFinal=0.5;
        }
        if(trim($structure)=='collection' || trim($structure)=='networked' ||trim($structure)=='hierarchical' || trim($structure)=='linear'){
            if(trim($aggregationLevel)=='2' || trim($aggregationLevel)=='3' || trim($aggregationLevel)=='4') $coherenciaLOMFinal=1; //0.5 para comprobar
        }
        
        //para formula de Ocohoa
        $coherenciaOchoa = Qcoh($titulo,$descripcion,$coherenciaLOMFinal);
        
        //actualizo metrica Coherencia
        //$meta->updateCoherencia($idOA,$coherenciaLOMFinal);
        $meta->updateCoherencia($idOA,round($coherenciaOchoa,2));
        echo $oa->getMaxId();//envio al FrontEnd un valor > 0
    }
    else echo -2;//El OA no se pudo insertar (subir) en el servidor    
}
//------------------------------
//          NINGUN STANDR
//------------------------------
else {
    echo -1;//No es DC ni LOM
}

//Calculo de Coherencia con Ochoa
function Qcoh($tit,$desc,$qcoh1){  
    $Pi=0;$PiCuadrado=0;
    $Qi=0;$QiCuadrado=0;
    $PiQiProducto=0;$numerador=0;
    //print_r(str_word_count($tit,1));
    //print_r(str_word_count($tit, 1, 'àáãç3'));
    
    //pasar a minusculas
    $tit=  strtolower($tit);
    $desc = strtolower($desc);
    
    //Quitar articulos
    $quitarArticulos = array(' mas. ',' el. ', 'no ',' tal ',' mas ', ' el ', ' la ', ' los ', ' ella ', ' un ', ' una ', ' unos ', ' unas  ', ' en ', ' con ', ' muy ', ' a ', ' y ', ' que ', ' es ', ' los ',' las ',' como ',' son ',' o ',' O ',' a ',' este ',' han ',' de ',' se ',' ya ',' del ',' por ',' lo ',' ha '); // para que sea palabras completas
    $tit = str_replace($quitarArticulos, ' ', ' '.$tit.' ');
    $desc = str_replace($quitarArticulos, ' ', ' '.$desc.' ');
    
    //Quitar caracteres especiales
    $tit = str_replace('Á', 'a',$tit);$tit = str_replace('á', 'a',$tit);
    $tit = str_replace('É', 'e',$tit);$tit = str_replace('é', 'e',$tit);
    $tit = str_replace('Í', 'i',$tit);$tit = str_replace('í', 'i',$tit);
    $tit = str_replace('Ó', 'o',$tit);$tit = str_replace('ó', 'o',$tit);
    $tit = str_replace('Ú', 'u',$tit);$tit = str_replace('ú', 'u',$tit);
    $tit = str_replace('Ñ', 'n',$tit);$tit = str_replace('ñ', 'n',$tit);
    
    $desc = str_replace('Á', 'a',$desc);$desc = str_replace('á', 'a',$desc);
    $desc = str_replace('É', 'e',$desc);$desc = str_replace('é', 'e',$desc);
    $desc = str_replace('Í', 'i',$desc);$desc = str_replace('í', 'i',$desc);
    $desc = str_replace('Ó', 'o',$desc);$desc = str_replace('ó', 'o',$desc);
    $desc = str_replace('Ú', 'u',$desc);$desc = str_replace('ú', 'u',$desc);
    $desc = str_replace('Ñ', 'n',$desc);$desc = str_replace('ñ', 'n',$desc);
    
    //Transformar en arreglo las palabras contenidas en $titulo y $descripcion
    $countPi = preg_match_all('/\pL+/u', $tit, $arrPiBruto); //$count=3 y $matches =(hola,mundo,hola)
    $Pi1 = array_values(array_unique($arrPiBruto[0]));//$Pi=(hola,mundo), quita elementos repetidos
    
    $countQi = preg_match_all('/\pL+/u', $desc, $arrQiBruto);
    $Qi1 = array_values(array_unique($arrQiBruto[0]));
    //print_r($Qi);
    
    //Unir los arreglos    
    $PiQiBruto=array_merge($Pi1,$Qi1);//contiene palabras del titulo (sin repetrise) y descripcion (sin repetrise), pero en la union pueden respetirse
    $PiQiNeto=array_values(array_unique($PiQiBruto));//contiene palabras del titulo y descripcion q no se repiten
    
    //Variables de la formula
    $n = count($PiQiNeto);//numero de palabras diferentes en $titulo + $descripcion que se compararan
    $k = 2; //numero de metadatos que describen al OA y cuya coherencia se analizara    
    
    //Calculo de Pi y Qi
    for($i=0;$i<count($PiQiNeto);$i++){
        $Pi = substr_count(implode('ALGOPARAJUNTAR', $arrPiBruto[0]), $PiQiNeto[$i]);//num de veces q aparece el termino $i en $tit
        $Qi = $Qi + substr_count(implode('ALGOPARAJUNTAR', $arrQiBruto[0]), $PiQiNeto[$i]);//num de veces q aparece el termino $i en $desc
        $PiQi = $Pi * $Qi;
        $PiAlCuadrado = $Pi * $Pi;
        $QiAlCuadrado = $Qi * $Qi;
        $PiQiProducto = $PiQiProducto + $PiQi;//sumatoria de productos en el numerador
        $PiCuadrado = $PiCuadrado + $PiAlCuadrado;
        $QiCuadrado = $QiCuadrado + $QiAlCuadrado;
    }
    //raiz de sumatorias de cuadrados en el denominador
    $raizPiQi = sqrt ($PiCuadrado * $QiCuadrado);
    //calculo el numerador de la formula general, segun el numero de metas analizadas (2: $tit y $desc)
    for($j=0;$j<$k;$j++){
        $numerador = $numerador + ($PiQiProducto / $raizPiQi);
    }
    //Calculo de la Coherencia
    $Qcoh = ($numerador / $k);
    //$promedio y calculo final de la Coherencia
    $Qcoh = ($qcoh1 + $Qcoh) / 2;
    
    if($Qcoh >= 0.7) $Qcoh = 1;
        
    return $Qcoh;
}
















/*
global $VGPathDocsServer; //'/var/www/html/apps/coac/docs/'

session_start();

$coac="";
$result=1;

$cliente = new recliente('');
$producto = new reproducto('');
$transaccion = new retransaccion('');
$banco = new rebanco('');

//borrar tablas
$cliente->delete();$producto->delete();$transaccion->delete();$banco->delete();

$filaCli=2;$filaPro=2;$filaTra=2;$filaBan=2;

if($_SESSION["mailUsuario"]=='info@coacsa.com') $coac='coacsa';

$filenameCli=$_FILES['fileCli']['name'];
$filepathCli=$_FILES['fileCli']['tmp_name'];
if(!move_uploaded_file($filepathCli, $VGPathDocsServer.$coac.'/resu/'.$filenameCli)) $result=0;
else{
    chmod($VGPathDocsServer.$coac.'/resu/'.$filenameCli, 0777);
    $dataCli = new Spreadsheet_Excel_Reader();
    $dataCli->setOutputEncoding('CP1251');
    $dataCli->read($VGPathDocsServer.$coac.'/resu/'.$filenameCli);
    while(trim($dataCli->sheets[0]['cells'][$filaCli][1]!=""))
    {
        //validarCli($filaCli);        
        $cliente->insert(trim($dataCli->sheets[0]['cells'][$filaCli][1]),trim($dataCli->sheets[0]['cells'][$filaCli][2]),
            trim($dataCli->sheets[0]['cells'][$filaCli][3]),trim($dataCli->sheets[0]['cells'][$filaCli][4]),
            trim($dataCli->sheets[0]['cells'][$filaCli][5]),trim($dataCli->sheets[0]['cells'][$filaCli][6]),
            trim($dataCli->sheets[0]['cells'][$filaCli][7]),trim($dataCli->sheets[0]['cells'][$filaCli][8]),
            trim($dataCli->sheets[0]['cells'][$filaCli][9]),trim($dataCli->sheets[0]['cells'][$filaCli][10]));
        $filaCli++;
    }    
}

$filenamePro=$_FILES['filePro']['name'];
$filepathPro=$_FILES['filePro']['tmp_name'];
if(!move_uploaded_file($filepathPro, $VGPathDocsServer.$coac.'/resu/'.$filenamePro)) $result=0;
else{
    chmod($VGPathDocsServer.$coac.'/resu/'.$filenamePro, 0777);
    $dataPro = new Spreadsheet_Excel_Reader();
    $dataPro->setOutputEncoding('CP1251');
    $dataPro->read($VGPathDocsServer.$coac.'/resu/'.$filenamePro);
    while(trim($dataPro->sheets[0]['cells'][$filaPro][1]!=""))
    {
        //validarPro($filaPro);
        $producto->insert(trim($dataPro->sheets[0]['cells'][$filaPro][2]),trim($dataPro->sheets[0]['cells'][$filaPro][3]),
            trim($dataPro->sheets[0]['cells'][$filaPro][4]),trim($dataPro->sheets[0]['cells'][$filaPro][5]),
            trim($dataPro->sheets[0]['cells'][$filaPro][6]));
        $filaPro++;
    }    
}

$filenameTra=$_FILES['fileTra']['name'];
$filepathTra=$_FILES['fileTra']['tmp_name'];
if(!move_uploaded_file($filepathTra, $VGPathDocsServer.$coac.'/resu/'.$filenameTra)) $result=0;
else{
    chmod($VGPathDocsServer.$coac.'/resu/'.$filenameTra, 0777);
    $dataTra = new Spreadsheet_Excel_Reader();
    $dataTra->setOutputEncoding('CP1251');
    $dataTra->read($VGPathDocsServer.$coac.'/resu/'.$filenameTra);
    while(trim($dataTra->sheets[0]['cells'][$filaTra][1]!=""))
    {
        //validarTra($filaTra);
        $transaccion->insert(trim($dataTra->sheets[0]['cells'][$filaTra][5]),trim($dataTra->sheets[0]['cells'][$filaTra][3]),
            trim($dataTra->sheets[0]['cells'][$filaTra][4]),intval(trim($dataTra->sheets[0]['cells'][$filaTra][6])),
            intval(trim($dataTra->sheets[0]['cells'][$filaTra][7])),intval(trim($dataTra->sheets[0]['cells'][$filaTra][8])),
            intval(trim($dataTra->sheets[0]['cells'][$filaTra][9])),intval(trim($dataTra->sheets[0]['cells'][$filaTra][10])),
            trim($dataTra->sheets[0]['cells'][$filaTra][11]),trim($dataTra->sheets[0]['cells'][$filaTra][12]),
            trim($dataTra->sheets[0]['cells'][$filaTra][13]),trim($dataTra->sheets[0]['cells'][$filaTra][14]),
            intval(trim($dataTra->sheets[0]['cells'][$filaTra][15])));
        $filaTra++;
    }    
}

$filenameBan=$_FILES['fileBan']['name'];
$filepathBan=$_FILES['fileBan']['tmp_name'];
if(!move_uploaded_file($filepathBan, $VGPathDocsServer.$coac.'/resu/'.$filenameBan)) $result=0;
else{
    chmod($VGPathDocsServer.$coac.'/resu/'.$filenameBan, 0777);
    $dataBan = new Spreadsheet_Excel_Reader();
    $dataBan->setOutputEncoding('CP1251');
    $dataBan->read($VGPathDocsServer.$coac.'/resu/'.$filenameBan);
    while(trim($dataBan->sheets[0]['cells'][$filaBan][1]!=""))
    {
        //validarBan($filaBan);
        $banco->insert(trim($dataBan->sheets[0]['cells'][$filaBan][4]),trim($dataBan->sheets[0]['cells'][$filaBan][7]),
            trim($dataBan->sheets[0]['cells'][$filaBan][8]),trim($dataBan->sheets[0]['cells'][$filaBan][9]),
            trim($dataBan->sheets[0]['cells'][$filaBan][10]),trim($dataBan->sheets[0]['cells'][$filaBan][11]));
        $filaBan++;
    }
}

echo $result;*/
?>