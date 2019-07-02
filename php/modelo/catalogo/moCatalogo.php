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

class catalogo extends conexion {

    var $ta_id;
    var $ca_id;
    var $ca_desc;
    var $ca_etiqueta;
    var $ca_subgrupo;
    var $ca_peso;
    var $arregloCatalogos;

    function catalogo($ta_id,$ca_id) {
        if ($ta_id > 0) {            
            $filtro = "";
            $indice = -1;
            if ($ca_id != '') //select de un Catalogo en particular
                $filtro = "WHERE ta_id=$ta_id AND ca_id='" . trim($ca_id) . "'";
            else {
                $indice = 0;
                $this->arregloCatalogos = array();
                $filtro = "WHERE ta_id=$ta_id";
                //select de todas los Catalogos 
            }

            $query = "SELECT * FROM mecatalogo $filtro";


            $result = $this->strSql($query);
            while (!$this->esUltimo($result)) {
                $this->setCatalogo($result);
                if ($indice != -1)
                    $this->arregloCatalogos[$indice] = $this->setArregloCatalogos($result);
                $result->MoveNext();
                $indice++;
            }
        }
    }

    function setCatalogo($result) {
        $this->ta_id = $this->getField($result, 0);
        $this->ca_id = $this->getField($result, 1);
        $this->ca_desc = $this->getField($result, 2);
        $this->ca_etiqueta = $this->getField($result, 3);
        $this->ca_subgrupo = $this->getField($result, 4);
        $this->ca_peso = $this->getField($result, 5);
    }

    function setArregloCatalogos($result) {
        $catalogo = new catalogo(0, '', 1);
        $catalogo->ta_id = $this->getField($result, 0);
        $catalogo->ca_id = $this->getField($result, 1);
        $catalogo->ca_desc = $this->getField($result, 2);
        $catalogo->ca_etiqueta = $this->getField($result, 3);
        $catalogo->ca_subgrupo = $this->getField($result, 4);
        $catalogo->ca_peso = $this->getField($result, 5);
        return $catalogo;
    }
}

?>