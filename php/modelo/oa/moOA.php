<?php
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
//require_once("../../../../clsConexion.php");
//require_once("../../../../global.php");

class oa extends conexion{

	var $oa_id; // Integer PK
	var $oa_titulo;
	var $oa_std;
        var $oa_url;
        var $oa_tot_completitud;
        var $oa_tot_consistencia;
        var $oa_tot_coherencia;
	var $arregloOA;
    
	function oa($oa_id)
	{
            if($oa_id>-1)
            {
                $filtro="";
                $indice=-1;
                if($oa_id>0) //un usuario en particular
                {
                    $filtro="WHERE oa_id=$oa_id";
                }
                else //=0 todos los usuarios
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

    
	function setOA($result)
	{
		$this->oa_id=$this->getField($result,0);	
		$this->oa_titulo=$this->getField($result,1);
		$this->oa_std=$this->getField($result,2);
                $this->oa_url=$this->getField($result,3);
                $this->oa_tot_completitud=$this->getField($result,4);
                $this->oa_tot_consistencia=$this->getField($result,5);
                $this->oa_tot_coherencia=$this->getField($result,6);
	}
    
	function setArregloOA($result)
	{
		$oa=new oa(-1);
		$oa->oa_id=$this->getField($result,0);	
		$oa->oa_titulo=$this->getField($result,1);
		$oa->oa_std=$this->getField($result,2);
                $oa->oa_url=$this->getField($result,3);
                $oa->oa_tot_completitud=$this->getField($result,4);
                $oa->oa_tot_consistencia=$this->getField($result,5);
                $oa->oa_tot_coherencia=$this->getField($result,6);
		return $oa;
	}

    
	function insert($oa_titulo,$oa_std,$oa_url)
	{
            $queryBusqueda="SELECT max(oa_id) FROM meoa";
            $max = $this->strSql($queryBusqueda);
            $oa_id = $this->getField($max, 0);
            if($oa_id>0) $oa_id = $oa_id+1;
            else $oa_id=1;
            
            $queryInsert="INSERT INTO meoa (oa_id,oa_titulo,oa_std,oa_url,oa_tot_completitud,oa_tot_consistencia,oa_tot_coherencia) 
                    VALUES($oa_id,'$oa_titulo','$oa_std','$oa_url',0,0,0)";

            $result = $this->strSql($queryInsert);

            if($result) return $this->getMaxId(); //exito > 0
            else return 0; //error 0
	}
	
	
	function update($oa_id,$oa_std,$oa_tot_completitud,$oa_tot_consistencia,$oa_tot_coherencia)
	{
            $query="UPDATE meoa set oa_std = '$oa_std',
                oa_tot_completitud=$oa_tot_completitud,
                oa_tot_consistencia=$oa_tot_consistencia,
                oa_tot_coherencia=$oa_tot_coherencia 
                WHERE oa_id= $oa_id";
            $result = $this->strSql($query);        
            if($result) return $oa_id; //exito >0
            else return 0; //error 0
	}
	
        function delete($oa_id)
	{ 
            $query="DELETE from meoa WHERE oa_id = $oa_id";
            $result = $this->strSql($query);
            return $result; //true o false
	}

        function getMaxId(){
            $query = "select max(oa_id) from meoa";
            $result = $this->select($query);
            return $this->getField($result, 0);
	}
}
?>