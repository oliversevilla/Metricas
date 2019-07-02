<?php
//requiere('config.ukuku.php');
require_once('libs/adodb5/adodb-exceptions.inc.php');
require_once('libs/adodb5/adodb.inc.php');
//require_once __DIR__.'../global.php';
require_once ('global.php');

class conexion
{
	var $db;
	function conexion()
	{

	}
	
	function select($queryBusqueda)
	{
                global $VGmiHost, $VGmiUser, $VGmiPass, $VGmiBdd;
        
		$this->db=ADONewConnection('postgres');
		//$this->db->Connect("localhost","sanehi","sanehi","db_ukuku_tur");
		$this->db->Connect($VGmiHost,$VGmiUser,$VGmiPass,$VGmiBdd);
		$result = $this->db->Execute($queryBusqueda);
		return $result;
	}
	
	function strSql($queryBusqueda)
	{
                global $VGmiHost, $VGmiUser, $VGmiPass, $VGmiBdd;
		$this->db=ADONewConnection('postgres');
		//$this->db->Connect("localhost","sanehi","sanehi","db_ukuku_tur");
		$this->db->Connect($VGmiHost,$VGmiUser,$VGmiPass,$VGmiBdd);		
		///////$result = $this->db->Execute($queryBusqueda);
		/////////return $result;
		
		
		//$DB = NewADOConnection('oci8');  
		//$DB->Connect("", "scott", "tiger");
		try {
		   return $this->db->Execute($queryBusqueda);
		} catch (exception $e) {
		    print_r($e);
		}
	}
	
	function siguiente($result)
	{
		return $result->EOF;
	}
	function esUltimo($result)
	{
		return $result->EOF;
	}
	
	function getField($result,$indice)
	{
		return $result->fields[$indice];
	}
	
	function charEspecial($query,$campo)
	{
		$DB = DB :: getInstance();
                $DB=ADONewConnection('postgres');
		$res=$DB->returnPreparedQuery($query,$campo);
		return $res;
	}
}
//TEST
//$rs = $db->Execute('select * from jos_imapax_suministroeeq');
//print "<pre>";
//print_r($rs->GetRows());
//print "</pre>";
?>