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
            
            //No existe para los metas analizados del DOM -> 1 para todos (coherente)
            $coherenciaDC=1;
            
            
            $meta->insert($idOA,$arrCat[$i]->ca_id,-1,$contenidoDC,$etiquetaDC,$completitudDC,$consistenciaDC,$coherenciaDC);
        }
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
        //actualizo metrica Coherencia
        $meta->updateCoherencia($idOA,$coherenciaLOMFinal);
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

exit;












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

echo $result;
?>