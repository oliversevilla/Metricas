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

class instancia extends conexion {

    var $ta_id;
    var $ca_id;
    var $in_id;
    var $in_nombre;
    var $arregloInstancia;

    function instancia($ta_id,$ca_id,$in_id) {
        if ($ta_id > 0) {            
            $filtro = "";
            $indice = -1;
            if ($in_id>0) //select de un Catalogo en particular
                $filtro = "WHERE ta_id=$ta_id AND ca_id='$ca_id' AND in_id=$in_id";
            else {
                $indice = 0;
                $this->arregloInstancia = array();
                $filtro = "WHERE ta_id=$ta_id AND ca_id='$ca_id'";
                //select de todas los Catalogos 
            }

            $query = "SELECT * FROM meinstancia $filtro";


            $result = $this->strSql($query);
            while (!$this->esUltimo($result)) {
                $this->setInstancia($result);
                if ($indice != -1)
                    $this->arregloInstancia[$indice] = $this->setArregloInstancia($result);
                $result->MoveNext();
                $indice++;
            }
        }
    }

    function setInstancia($result) {
        $this->ta_id = $this->getField($result, 0);
        $this->ca_id = $this->getField($result, 1);
        $this->in_id = $this->getField($result, 2);
        $this->in_nombre = $this->getField($result, 3);
    }

    function setArregloInstancia($result) {
        $instancia = new instancia(0, '', 0);
        $instancia->ta_id = $this->getField($result, 0);
        $instancia->ca_id = $this->getField($result, 1);
        $instancia->in_id = $this->getField($result, 2);
        $instancia->in_nombre = $this->getField($result, 3);
        return $instancia;
    }
}

?>