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
class repo extends conexion{
    //creacion de atributos de clase
    var $re_id;//PK
    var $re_dominio;
    var $re_url;
    var $arregloRepo;
    //constructor
    function repo($re_id)
    {
        if($re_id>-1)
        {
            $filtro="";
            $indice=-1;
            if($re_id>0) //un repo en particular
            {
                $filtro="WHERE re_id=$re_id";
            }
            else //=0 todos los repo
            {
                $indice=0;
                $this->arregloRepo=array();
                $filtro="ORDER BY re_dominio ASC";
            }
            $query="SELECT * FROM merepo $filtro";
            $result = $this->strSql($query);                                

            while (!$this->esUltimo($result))
            {		
                $this->setRepo($result);
                if($indice!=-1)
                    $this->setRepo[$indice]=$this->setArregloRepo($result);
                $result->MoveNext();
                $indice++;
            }
        }
    }

    //creacion de objeto de clase
    function setRepo($result)
    {
            $this->re_id=$this->getField($result,0);	
            $this->re_dominio=$this->getField($result,1);	
            $this->re_url=$this->getField($result,2);
    }
    //creacion de artreglo de objetos de clase
    function setArregloRepo($result)
    {
            $repo=new repo(-1);
            $repo->re_id=$this->getField($result,0);
            $repo->re_dominio=$this->getField($result,1);	
            $repo->re_url=$this->getField($result,2);
            return $repo;
    }

    //********************************
    //Creacion de metodos de la clase
    //********************************
    
    function insert($re_dominio,$re_url)
    {
        $queryBusqueda="SELECT max(re_id) FROM merepo";
        $max = $this->strSql($queryBusqueda);
        $re_id = $this->getField($max, 0);
        if($re_id>0) $re_id = $re_id+1;
        else $re_id=1;

        $queryInsert="INSERT INTO merepo (re_id,re_dominio,re_url) 
                VALUES($re_id,'$re_dominio','$re_url')";

        $result = $this->strSql($queryInsert);

        if($result) return $this->getMaxId(); //exito > 0
        else return 0; //error 0
    }


    function update($re_id,$re_dominio,$re_url)
    {
        $query="UPDATE merepo set re_dominio = '$re_dominio',
            re_url='$re_url' 
            WHERE re_id=$re_id";
        $result = $this->strSql($query);        
        if($result) return $oa_id; //exito >0
        else return 0; //error 0
    }

    function delete($re_id)
    { 
        $query="DELETE from merepo WHERE re_id=$re_id";
        $result = $this->strSql($query);
        return $result; //true o false
    }

    function getMaxId(){
        $query = "select max(re_id) from merepo";
        $result = $this->select($query);
        return $this->getField($result, 0);
    }
    
    function exists($re_dominio){
        $query = "select re_id from merepo where re_dominio='$re_dominio'";
        $result = $this->select($query);
        return $this->getField($result, 0);
    }
}
?>