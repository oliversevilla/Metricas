<?php
//carga settings de la bdd
if (is_file("../clsConexion.php")){
    require_once("../clsConexion.php");
    require_once("../global.php");
}
elseif(is_file("../../clsConexion.php")){//desde inicio.php
    require_once("../../clsConexion.php");
    require_once("../../global.php");
}
elseif(is_file("../../../clsConexion.php")){//desde inicio.php
    require_once("../../../clsConexion.php");
    require_once("../../../global.php");
}
elseif(is_file("../../../../clsConexion.php")){
    require_once("../../../../clsConexion.php");
    require_once("../../../../global.php");
}
else{
    require_once("clsConexion.php");//desde FE
    require_once("global.php");//desde FE
}
//creacion de la clase
class meta extends conexion
{
    //creacion de atributos de clase
    var $re_id;
    var $oa_id;
    var $me_id;
    var $me_val;
    var $me_content;
    var $me_etiqueta;
    var $me_completitud;
    var $me_consistencia;
    var $me_coherencia;
    var $arregloMeta;
    //constructor
    function meta($oa_id,$me_id)
    {
        if($oa_id>0)
        {
            $filtro="";
            $indice=-1;
            if($me_id!='') //select de una meta en particular
            {        
                $filtro="WHERE oa_id=$oa_id AND me_id='$me_id'";
            }
            else //select de todas las metadatas del OA
            {
                $indice=0;
                $this->arregloMeta=array();
                $filtro="WHERE oa_id=$oa_id";
            }			

            $query="SELECT * FROM memeta $filtro";

            $result = $this->strSql($query);                                

            while (!$this->esUltimo($result))
            {		
                $this->setMeta($result);
                if($indice!=-1)
                        $this->arregloMeta[$indice]=$this->setArregloMeta($result);
                $result->MoveNext();
                $indice++;
            }
        }
    }
    //creacion de objeto de clase
    function setMeta($result)
    {
        $this->re_id=$this->getField($result,0);
        $this->oa_id=$this->getField($result,1);
        $this->me_id=$this->getField($result,2);
        $this->me_val=$this->getField($result,3);
        $this->me_content=$this->getField($result,4);
        $this->me_etiqueta=$this->getField($result,5);
        $this->me_completitud=$this->getField($result,6);
        $this->me_consistencia=$this->getField($result,7);
        $this->me_coherencia=$this->getField($result,8);
    }
    //creacion de artreglo de objetos de clase
    function setArregloMeta($result)
    {
        $meta=new meta(0,'');
        $meta->re_id=$this->getField($result,0);
        $meta->oa_id=$this->getField($result,1);
        $meta->me_id=$this->getField($result,2);
        $meta->me_val=$this->getField($result,3);
        $meta->me_content=$this->getField($result,4);
        $meta->me_etiqueta=$this->getField($result,5);
        $meta->me_completitud=$this->getField($result,6);
        $meta->me_consistencia=$this->getField($result,7);
        $meta->me_coherencia=$this->getField($result,8);
        return $meta;
    }
    
    //********************************
    //Creacion de metodos de la clase
    //********************************
    
    function insert($re_id,$oa_id,$me_id,$me_val,$me_content,$me_etiqueta,$completitud,$consistencia,$coherencia)
    {
        if($completitud==0 || $completitud=='' || !isset($completitud)) $completitud=0;
        if($consistencia==0 || $consistencia=='' || !isset($consistencia)) $consistencia=0;
        if($coherencia==0 || $coherencia=='' || !isset($coherencia)) $coherencia=0;
        $query="INSERT INTO memeta (re_id,oa_id,me_id,me_val,me_content,me_etiqueta,me_completitud,me_consistencia,me_coherencia) 
        VALUES($re_id,$oa_id,'$me_id',$me_val,'$me_content','$me_etiqueta',$completitud,$consistencia,$coherencia)";
        //return $query;
        $result = $this->strSql($query);
        if($result) {
            return 1;
            /*$queryOA="SELECT MAX(oa_id) FROM meoa";
            $maxOA = $this->strSql($queryOA);
            if($maxOA) return $maxOA; //>0 exito
            else return -3;*/
        }
        else return -3;//error
    }
    
    //Actualizar
    function update($oa_id,$me_id,$me_val,$me_content,$me_etiqueta,$completitud,$consistencia,$coherencia)
    {
        if($completitud==0 || $completitud=='' || !isset($completitud)) $completitud=0;
        if($consistencia==0 || $consistencia=='' || !isset($consistencia)) $consistencia=0;
        if($coherencia==0 || $coherencia=='' || !isset($coherencia)) $coherencia=0;
        $query="UPDATE memeta SET me_val=$me_val,me_content='$me_content',me_etiqueta='$me_etiqueta',
        me_comletitud=$completitud,me_consistencia=$consistencia,me_coherencia=$coherencia 
        WHERE oa_id=$oa_id AND me_id='$me_id'";
        
        $result = $this->strSql($query);        
        if($result) return $oa_id;
        else return 0; 
    }
    
    function delete($oa_id)
    { 
        $query="DELETE from memeta WHERE oa_id = $oa_id";
        $result = $this->strSql($query);
        return $result; //true o false
    }
    
    //Actualizar
    function updateCoherencia($oa_id,$coherencia)
    {
        if($coherencia==0 || $coherencia=='' || !isset($coherencia)) $coherencia=0;
        
        $query="UPDATE memeta SET me_coherencia=$coherencia 
        WHERE oa_id=$oa_id and me_etiqueta IN ('title','description','dc_title','dc_description')";
        
        $result = $this->strSql($query);        
        if($result) return $oa_id;
        else return 0; 
    }
}
