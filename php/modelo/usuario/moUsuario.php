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

class usuario extends conexion{

	var $us_id; // Integer PK
	var $us_nombre;
	var $us_apellido;
	var $us_mail;
	var $us_clave;
	var $us_clave_activacion;
        var $us_rol;
        var $us_fec_ini;
        var $us_fec_fin;
	var $us_estado;
	var $us_avatar;
	var $us_sin_encriptar;
        //var $us_coac;
        var $arregloUsuario;
    
	function usuario($us_id)
	{
            if($us_id>-1)
            {
                $filtro="";
                $indice=-1;
                if($us_id>0) //un usuario en particular
                {
                    $filtro="WHERE us_id=$us_id";
                }
                else //=0 todos los usuarios
                {
                    $indice=0;
                    $this->arregloUsuario=array();
                    $filtro="ORDER BY us_id DESC";
                }
                $query="SELECT * FROM usuario $filtro";
                $result = $this->strSql($query);                                

                while (!$this->esUltimo($result))
                {		
                    $this->setUsuario($result);
                    if($indice!=-1)
                        $this->setUsuario[$indice]=$this->setArregloUsuario($result);
                    $result->MoveNext();
                    $indice++;
                }
            }
	}

    
	function setUsuario($result)
	{
		$this->us_id=$this->getField($result,0);	
		$this->us_nombre=$this->getField($result,1);
		$this->us_apellido=$this->getField($result,2);
		$this->us_mail=$this->getField($result,3);
		$this->us_clave=$this->getField($result,4);
		$this->us_clave_activacion=$this->getField($result,5);
		$this->us_rol=$this->getField($result,6);
		$this->us_fec_ini=$this->getField($result,7);
		$this->us_fec_fin=$this->getField($result,8);
		$this->us_estado=$this->getField($result,9);
                $this->us_avatar=$this->getField($result,10);
                $this->us_sin_encriptar=$this->getField($result,11);
                //$this->us_coac=$this->getField($result,12);
	}
    
	function setArregloUsuario($result)
	{
		$usuario=new usuario(-1);
		$usuario->us_id=$this->getField($result,0);	
		$usuario->us_nombre=$this->getField($result,1);
		$usuario->us_apellido=$this->getField($result,2);
		$usuario->us_mail=$this->getField($result,3);
		$usuario->us_clave=$this->getField($result,4);
		$usuario->us_clave_activacion=$this->getField($result,5);
		$usuario->us_rol=$this->getField($result,6);
		$usuario->us_fec_ini=$this->getField($result,7);
		$usuario->us_fec_fin=$this->getField($result,8);
		$usuario->us_estado=$this->getField($result,9);                
                $usuario->us_avatar=$this->getField($result,10);
                $usuario->us_sin_encriptar=$this->getField($result,11);
                //$usuario->us_coac=$this->getField($result,12);
		return $usuario;
	}

    //function insertar($id_id,$pa_id,$fe_id,$fere_nombre,$fere_descripcion,$fere_categoria,$se_id)
    //{   
        /*$where = "WHERE id_id=$id_id and pa_id=$pa_id and fe_id=$fe_id";
        $queryUpdate="UPDATE guutfestivo SET fe_calificacion=fe_calificacion+$calificacion,fe_total_votos=fe_total_votos+1 $where";
        $result = $this->select($queryUpdate);
        return $result; //true o false*/
    //}
        
        function login($us_mail,$us_clave)
	{ 
		$query="SELECT * FROM usuario WHERE us_mail='".trim($us_mail)."' AND us_clave=md5('".trim($us_clave)."')";
		
                $result = $this->strSql($query);
                $this->setUsuario($result);
		
                if($this->us_id>0){
                    if(trim($this->us_estado)=='ACTIV') return $this->us_id; //exito >1
                    else return 0; //INACTIVO 0
                }
                else return -1; //No existe
	}
        
	function obtener($us_id)
	{
		$queryBusqueda="SELECT * FROM usuario WHERE us_id=$us_id AND us_estado='ACTIV'";
		$result = $this->select($queryBusqueda);        
        
		if(!$this->siguiente($result))
		{
			$this->setUsuario($result);
			return $this;
		}
		else
			return 0;
	}
    
	function obtenerMail($us_mail) //Obtener dado el string us_mail
	{
		$queryBusqueda="SELECT * FROM usuario WHERE us_mail='".$us_mail."' AND us_estado='ACTIV'";
		$result = $this->select($queryBusqueda);        
        
		if(!$this->siguiente($result))
		{
			$this->setUsuario($result);
			return $this;
		}
		//else
		//	return 0;
	}
	
	function obtenerClaveActivacion($us_clave_activacion) //Obtener dado el string us_clave_activacion
	{
		$queryBusqueda="SELECT * FROM usuario WHERE us_clave_activacion='".$us_clave_activacion."'";
		$result = $this->select($queryBusqueda);        
        
		if(!$this->siguiente($result))
		{
			$this->setUsuario($result);
			return $this;
		}
		//else
		//	return 0;
	}
	
	function insert($us_nombre,$us_apellido,$us_mail,$us_clave)
	{
            $usuarioValido = $this->obtenerMail($us_mail);
            if($usuarioValido->us_id > 0) return 0;//mail ya existe
            else
            {
                
                $queryBusqueda="SELECT max(us_id) FROM usuario";
                $max = $this->strSql($queryBusqueda);
                $us_id = $this->getField($max, 0);
                if($us_id>0){
                    $queryInsert="INSERT INTO usuario (
                            us_id,us_nombre,us_apellido,us_mail,us_clave,us_rol,us_fec_ini,us_estado,us_sin_encriptar) 
                            VALUES($us_id+1,'$us_nombre','$us_apellido','$us_mail',
                                md5('$us_clave'),'NEGOC',CURRENT_TIMESTAMP,'ACTIV','$us_clave')";
                    
                    $result = $this->strSql($queryInsert);

                    if($result) return $this->getMaxId(); //exito > 0
                    else return 0; //error 0
                }
                
            }
	}
	
	
	function update($us_id,$us_nombre,$us_apellido,$us_mail,$us_clave,$us_estado)
	{
            $query="UPDATE usuario set us_nombre = '$us_nombre',
            us_apellido = '$us_apellido',
            us_mail = '$us_mail',            
            us_clave = md5('$us_clave'),
            us_estado = '$us_estado',
            us_sin_encriptar = '$us_clave' 
            WHERE us_id= $us_id";
            //$result = $this->strSql($query);
            //return $result; //true o false                
            $result = $this->strSql($query);        
            if($result) return $us_id; //exito >0
            else return 0; //error 0
	}
	
        function delete($us_id)
	{ 
		$query="DELETE from usuario WHERE us_id = $us_id";
		$result = $this->strSql($query);
		return $result; //true o false
	}

	function actualizarEstado($us_clave_activacion)
	{ 
		$query="UPDATE usuario set us_estado='ACTIV' WHERE us_clave_activacion='".$us_clave_activacion."'";
		$result = $this->strSql($query);
		return $result; //true o false
	}
	
	function actualizarClave($us_id,$us_clave)
	{ 
		$query="UPDATE utusuario set us_clave='".md5(trim($us_clave))." WHERE us_id=$us_id";
		$result = $this->strSql($query);
		return $result; //true o false
	}


        function getMaxId(){
            $query = "select max(us_id) from usuario";
            $result = $this->select($query);
            return $this->getField($result, 0);
	}
        
        function getAllCoac($us_coac)
        {
            //Trae inclusive Usuarios INACTIVOS
            $query="SELECT * FROM usuario WHERE us_coac='$us_coac' ORDER BY us_id DESC";
            $result = $this->strSql($query);
            $indice=0;
            $this->arregloUsuario=array();
            while (!$this->esUltimo($result))
            {		
                    $this->arregloUsuario[$indice]=$this->setArregloUsuario($result);
                    $result->MoveNext();
                    $indice++;
            }
        }
        
        //Obyiene usuarios q aun no estan ligados a una empresa
        function getAllCoacSinEmpresa($us_coac)
        {
            //Trae inclusive Usuarios INACTIVOS
            $query="SELECT * FROM usuario WHERE us_coac='$us_coac' AND us_id NOT IN (SELECT us_id FROM empresa) ORDER BY us_id DESC";
            $result = $this->strSql($query);
            $indice=0;
            $this->arregloUsuario=array();
            while (!$this->esUltimo($result))
            {		
                    $this->arregloUsuario[$indice]=$this->setArregloUsuario($result);
                    $result->MoveNext();
                    $indice++;
            }
        }
        
        function getAllPaginadoCoac($TAMANO_PAGINA,$inicio,$us_coac)
        {
            //Trae inclusive Usuarios INACTIVOS
            $query="SELECT * FROM usuario WHERE us_coac='$us_coac' ORDER BY us_id DESC LIMIT '$TAMANO_PAGINA' OFFSET '$inicio'";
            $result = $this->strSql($query);
            $indice=0;
            $this->arregloUsuario=array();
            while (!$this->esUltimo($result))
            {		
                    $this->arregloUsuario[$indice]=$this->setArregloUsuario($result);
                    $result->MoveNext();
                    $indice++;
            }
        }
        
        function getTotalByCoacFE($us_coac){
            $query = "SELECT count(*) FROM usuario WHERE us_coac='$us_coac' AND us_estado='ACTIV'";
            $result = $this->select($query);
            return $this->getField($result, 0);
        }

}
?>