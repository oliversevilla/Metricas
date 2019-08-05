<?php
require_once("../../../global.php");
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/instancia/moInstancia.php");
require_once("../../modelo/repo/moRepo.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/meta/moMeta.php");

//Variables comunes
$url = $_REQUEST['url'];
$dominio =  dominio($url);
$tags = get_meta_tags($url);//busca y almacena la estructura Doublin Core
$xml = simplexml_load_file($url);//busca y almacena la estructura LOM

//Creacion de objetos
$repo=new repo(-1);
$oa = new oa(0);
$meta = new meta(0,'');


//------------------------------
//          DUBLIN CORE
//------------------------------
if(isDcStd($url,$tags)) {
    
    //determinar si el oa (url) ya existe en tabla "meoa"
    $oaIdExist = $oa->exists($url);
    if($oaIdExist>0) echo -4;//"meoa" existente    
    else {    
        //determinar si el repositorio (dominio) ya existe en tabla "merepo"
        $repoIdExist = $repo->exists(trim($dominio));
        if($repoIdExist>0) $idRepo = $repoIdExist;//"merepo" id existente    
        else $idRepo = $repo->insert($dominio,$url);//insertar en la tabla "merepo"

        if($idRepo > 0){
            //guardo variables del OA para insertar en la tabla "meoa"
            $titulo=$tags['dc_title'];
            $std='DC';
            $idOA=$oa->insert($idRepo,$titulo,$std,$url);
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
                    /***************************
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
                    **********************/
                    $consistenciaDC=-1;
                    //--------------------Evaluar formula Coherencia
                    //para formula de Ochoa
                    if(trim($etiquetaDC=='dc_title')) $titulo=$contentDC;
                    if(trim($etiquetaDC=='dc_description')) $descripcion=$contentDC;
                    //No existe para los metas analizados del DOM -> 1 para todos (coherente)
                    $coherenciaDC=-1;

                    $meta->insert($idRepo,$idOA,$arrCat[$i]->ca_id,-1,$contenidoDC,$etiquetaDC,$completitudDC,$consistenciaDC,$coherenciaDC);
                }

                //para formula de Ocohoa
                /////////////////$coherenciaOchoa = Qcoh($titulo,$descripcion,$coherenciaDC);
                $coherenciaOchoa = Qcoh($titulo,$descripcion,-1);

                //actualizo metrica Coherencia        
                $meta->updateCoherencia($idOA,round($coherenciaOchoa,2)); 
                echo $oa->getMaxId();
            }
            else echo -2;//El OA no se pudo insertar (subir) en el servidor
        }
        else echo -3;//El Repo no se pudo insertar (subir) en el servidor
    }
}
//------------------------------
//              LOM
//------------------------------
//elseif($xml->general->title->string!='') {
elseif(isLomStd($xml)){    
    //determinar si el repositorio (dominio) ya existe en tabla "merepo"
    $repoIdExist = $repo->exists(trim($dominio));
    if($repoIdExist>0) $idRepo = $repoIdExist;//"merepo" id existente    
    else $idRepo=$repo->insert($dominio,$url);//insertar en la tabla "merepo"
    
    if($idRepo > 0){        
        $titulo=$xml->general->title->string;
        $std='LOM';

        $idOA=$oa->insert($idRepo,$titulo,$std,$url);    

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
                    $permitido=0;////////////////0;//0 si el meta no esta en valores permitidos
                    for($j=0;$j<count($arrInstancia);$j++){
                        if($contentLOM==$arrInstancia[$j]->in_nombre) $permitido=1;
                    }
                }
                else{
                    if($contentLOM!="") {
                        $contenidoLOM=$contentLOM;
                        $permitido=-1;//1; 
                    }
                    else {
                        $contenidoLOM="";
                        $permitido=-1;///////////////////0;
                    }                
                }            
                $consistenciaLOM=$permitido;//al final en la formula sumar y dividir para el total de metas, y actualizar en meoa
                //--------------------Evaluar formula Coherencia
                if(trim($etiquetaLOM=='structure')) $structure=$contentLOM;
                if(trim($etiquetaLOM=='aggregationLevel')) $aggregationLevel=$contentLOM;

                $coherenciaLOM=-1;

                $meta->insert($idRepo,$idOA,$arrCat[$i]->ca_id,-1,$contenidoLOM,$etiquetaLOM,$completitudLOM,$consistenciaLOM,$coherenciaLOM);

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
    else echo -3;//El Repo no se pudo insertar (subir) en el servidor
}
//------------------------------
//          NINGUN STANDR
//------------------------------
else {
    echo -1;//No es DC ni LOM
}

/*
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
    $quitarArticulos = array(' mas. ',' el. ', ' no ',' tal ',' mas ', ' el ', ' la ', ' los ', ' ella ', ' un ', ' una ', ' unos ', ' unas  ', ' en ', ' con ', ' muy ', ' a ', ' y ', ' que ', ' es ', ' los ',' las ',' como ',' son ',' o ',' O ',' a ',' este ',' han ',' de ',' se ',' ya ',' del ',' por ',' lo ',' ha '); // para que sea palabras completas
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
        $Qi = substr_count(implode('ALGOPARAJUNTAR', $arrQiBruto[0]), $PiQiNeto[$i]);//num de veces q aparece el termino $i en $desc
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
    if($qcoh1>=0) $Qcoh = ($qcoh1 + $Qcoh) / 2;
    
    //if($Qcoh >= 0.7) $Qcoh = 1;
        
    return $Qcoh;
}

function dominio($url){
    $protocolos = array('http://', 'https://', 'ftp://', 'www.');
    $url = explode('/', str_replace($protocolos, '', $url));
    return $url[0];
}

function isDcStd($url,$tags){
    $isDC=0;
    //Verificar Std DC con etiqueta LINK y atributo REL=schema.EC
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTMLFile($url);
    $links = $doc->getElementsByTagName('link');    
    
    for ($i = 0; $i < $links->length; $i++)
    {
        $link = $links->item($i);
        if($link->getAttribute('rel') == 'schema.DC') $isDC = 1;
    }
    //Si no puede verificar con etiqueta LINK, hacerlo con META = 'dc_title'
    if($isDC==0){
        if($tags['dc_title']!='') $isDC=1;
    }
    return $isDC;
}

function isLomStd($xml){
    $isLom=0;
    //Es un xml file valido?
    if(!$xml) return 0;
    else{
        //Verificar Std LOM con etiqueta Lom y atributo xsi=...ieee.org/...
        $att_sl = $xml->attributes("xsi",1);
        if($att_sl["schemaLocation"]=='http://ltsc.ieee.org/xsd/LOM lom.xsd') $isLom=1;
        //Si no puede verificar con etiqueta Lom, hacerlo con etiqueta = 'title'
        if($isLom==0){
            if($xml->general->title->string!='') $isLom=1;
        }
        return $isLom;
    }
}
*/
?>