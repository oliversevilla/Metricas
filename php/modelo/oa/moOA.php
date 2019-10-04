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
class oa extends conexion{
    //creacion de atributos de clase
    var $re_id;//PK,FK
    var $oa_id; // Integer PK
    var $oa_titulo;
    var $oa_std;
    var $oa_url;
    var $oa_tot_completitud;
    var $oa_tot_consistencia;
    var $oa_tot_coherencia;
    var $oa_fec;
    var $re_dominio;
    var $arregloOA;
    var $arregloOARepo;
    //constructor
    function oa($oa_id)
    {
        if($oa_id>0)
        {
            $filtro="";
            $indice=-1;
            if($oa_id>0) //un oa en particular
            {
                $filtro="WHERE oa_id=$oa_id";
            }
            else //=0 todos los oa de 1 repo
            {
                $indice=0;
                $this->arregloOA=array();
                $filtro="ORDER BY oa_id DESC";
            }
            $query="SELECT * FROM meoa $filtro";
            $result = $this->strSql($query);                                

            while (!$this->esUltimo($result))
            {		
                $this->setOA($result);
                if($indice!=-1)
                    $this->setOA[$indice]=$this->setArregloOA($result);
                $result->MoveNext();
                $indice++;
            }
        }
    }

    //creacion de objeto de clase
    function setOA($result)
    {
            $this->re_id=$this->getField($result,0);	
            $this->oa_id=$this->getField($result,1);	
            $this->oa_titulo=$this->getField($result,2);
            $this->oa_std=$this->getField($result,3);
            $this->oa_url=$this->getField($result,4);
            $this->oa_tot_completitud=$this->getField($result,5);
            $this->oa_tot_consistencia=$this->getField($result,6);
            $this->oa_tot_coherencia=$this->getField($result,7);
            $this->oa_fec=$this->getField($result,8);
    }
    //creacion de artreglo de objetos de clase
    function setArregloOA($result)
    {
            $oa=new oa(0);
            $oa->re_id=$this->getField($result,0);
            $oa->oa_id=$this->getField($result,1);	
            $oa->oa_titulo=$this->getField($result,2);
            $oa->oa_std=$this->getField($result,3);
            $oa->oa_url=$this->getField($result,4);
            $oa->oa_tot_completitud=$this->getField($result,5);
            $oa->oa_tot_consistencia=$this->getField($result,6);
            $oa->oa_tot_coherencia=$this->getField($result,7);
            $oa->oa_fec=$this->getField($result,8);
            return $oa;
    }
    
    //********************************
    //Creacion de metodos de la clase
    //********************************
    
    function setArregloOARepo($result)
    {
            $oa=new oa(0);
            $oa->re_id=$this->getField($result,0);
            $oa->oa_id=$this->getField($result,1);	
            $oa->oa_titulo=$this->getField($result,2);
            $oa->oa_std=$this->getField($result,3);
            $oa->oa_url=$this->getField($result,4);
            $oa->oa_tot_completitud=$this->getField($result,5);
            $oa->oa_tot_consistencia=$this->getField($result,6);
            $oa->oa_tot_coherencia=$this->getField($result,7);
            $oa->oa_fec=$this->getField($result,8);
            $oa->re_dominio=$this->getField($result,9);
            return $oa;
    }

    function insert($re_id,$oa_titulo,$oa_std,$oa_url)
    {
        $queryBusqueda="SELECT max(oa_id) FROM meoa";// WHERE re_id=$re_id";
        $max = $this->strSql($queryBusqueda);
        $oa_id = $this->getField($max, 0);
        if($oa_id>0) $oa_id = $oa_id+1;
        else $oa_id=1;

        $queryInsert="INSERT INTO meoa (re_id,oa_id,oa_titulo,oa_std,oa_url,oa_tot_completitud,oa_tot_consistencia,oa_tot_coherencia,oa_fec) 
                VALUES($re_id,$oa_id,'$oa_titulo','$oa_std','$oa_url',0,0,0,CURRENT_TIMESTAMP)";

        $result = $this->strSql($queryInsert);

        //if($result) return $this->getMaxId($re_id); //exito > 0
        if($result) return $this->getMaxId();
        else return 0; //error 0
    }


    function update($oa_id,$oa_std,$oa_tot_completitud,$oa_tot_consistencia,$oa_tot_coherencia)
    {
        $query="UPDATE meoa set oa_std = '$oa_std',
            oa_tot_completitud=$oa_tot_completitud,
            oa_tot_consistencia=$oa_tot_consistencia,
            oa_tot_coherencia=$oa_tot_coherencia,
            oa_fec=CURRENT_TIMESTAMP 
            WHERE oa_id= $oa_id";
        $result = $this->strSql($query);        
        if($result) return $oa_id; //exito >0
        else return 0; //error 0
    }

    function delete($oa_url)
    { 
        $query="DELETE from meoa WHERE oa_url = '$oa_url'";
        $result = $this->strSql($query);
        return $result; //true o false
    }

    //function getMaxId($re_id){
    function getMaxId(){
        $query = "select max(oa_id) from meoa";// WHERE re_id=$re_id";
        $result = $this->select($query);
        return $this->getField($result, 0);
    }
    
    function getId($oa_url){
        $query = "select oa_id from meoa where oa_url='$oa_url'";// WHERE re_id=$re_id";
        $result = $this->select($query);
        return $this->getField($result, 0);
    }
        
    function exists($re_url){
        $query = "select oa_id from meoa where oa_url='$re_url'";
        $result = $this->select($query);
        return $this->getField($result, 0);
    }
    
    //Obtener todos los OA analizados
    function getAll()
    {
        $query="SELECT o.*,r.re_dominio FROM meoa o JOIN merepo r ON (o.re_id = r.re_id) ORDER BY oa_fec DESC";
        
        $result = $this->strSql($query);
        $indice=0;
        $this->arregloOARepo=array();
        while (!$this->esUltimo($result))
        {		
                $this->arregloOARepo[$indice]=$this->setArregloOARepo($result);
                $result->MoveNext();
                $indice++;
        }
    }
    
    //Obtener todos los OA analizados dado re_id
    function getAllRepo($re_id)
    {
        if($re_id>0)
            $query="SELECT o.*,r.re_dominio FROM meoa o JOIN merepo r ON (o.re_id = r.re_id) WHERE r.re_id=$re_id ORDER BY oa_fec DESC";
        else
            $query="SELECT o.*,r.re_dominio FROM meoa o JOIN merepo r ON (o.re_id = r.re_id) ORDER BY oa_fec DESC";
        
        $result = $this->strSql($query);
        $indice=0;
        $this->arregloOARepo=array();
        while (!$this->esUltimo($result))
        {		
                $this->arregloOARepo[$indice]=$this->setArregloOARepo($result);
                $result->MoveNext();
                $indice++;
        }
    }
}
?>