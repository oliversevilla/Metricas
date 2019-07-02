<?php
$VGmiHost = "localhost";
//$VGmiHost = "162.248.55.72";
$VGmiUser = "postgres";
$VGmiPass = "sasa";
$VGmiBdd = "db_metricas";
$VGtamanoPagina=10; //$TAMANO_PAGINA para paginacion
$VGRegistrosPorPagina=12;
//String de conexion en las capas/layers
$VGstrConexion = "host=$VGmiHost dbname=$VGmiBdd user=$VGmiUser password=$VGmiPass port=5432";
$VGBaseHref="http://ecommerce.paisturistico.com/";//Aca poner dominio segun COAC
$VGCoac="ANDALUCI";
//$VGPathImagesServer = 'C:/ms4w/Apache/htdocs/ptadmin/img/';
//$VGPathImagesAdminServer = 'C:/ms4w/Apache/htdocs/ptadmin/img/';//linux "/var/www/...."
//$VGSlash="/";//Windows "\\"
$VGLon=-78.48429; //la carolina
$VGLat=-0.18284;

$VGPathImagesServer = '/var/www/html/apps/coac/img/';
$VGPathImagesAdminServer = '/var/www/html/apps/coac/img/';//linux "/var/www/...."
$VGPathDocsServer = '/var/www/html/apps/coac/docs/';//Docs Tasa de Interes
$VGSlash="\\";//linux "\\"


function formatoFecha($fechavieja,$nombreTabla) //formato Y-m-d
{
    list($fec,$time)=explode(" ",$fechavieja);
    list($a,$m,$d)=explode("-",$fec);
    list($h,$mi,$s)=explode(":",$time);
    $dia=nameDate($d.'/'.$m.'/'.$a);
    list($diaTxt,$mes,$anio)=explode("/",$dia);
    if($nombreTabla=='guutevento')
        return $diaTxt." ".$d."/".$m."/".$a.", ".$h."H".$mi;
    else //tabla es "guutfestivo"
	return $diaTxt." ".$d."/".$m."/".$a;
}

function formatoFechaNroDiaNomMes($fechavieja)
{
	session_start();
	if(isset($_SESSION['idiomaId']))
		$idiomaId=$_SESSION['idiomaId'];
	else
		$idiomaId=1;
		
	list($fec,$time)=explode(" ",$fechavieja);
	list($a,$m,$d)=explode("-",$fec);
	list($h,$mi,$s)=explode(":",$time);
	if($idiomaId==1)
		return $d." de ".nameMonthYearWithMonth($m);
	else
		return nameMonthYearWithMonth($m).' '.$d.' th';
}

function nameDate($fecha='')//formato: dd/mm/aaaa
{ 	
	session_start();
	if(isset($_SESSION['idiomaId']))
		$idiomaId=$_SESSION['idiomaId'];
	else
		$idiomaId=1;
		
	$fecha= empty($fecha)?date('d/m/Y'):$fecha;
	if($idiomaId==1)
		$dias = array('domingo','lunes','martes','mi�rcoles','jueves','viernes','s�bado');
	else
		$dias = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
	$dd   = explode('/',$fecha);
	$ts   = mktime(0,0,0,$dd[1],$dd[0],$dd[2]);
	return $dias[date('w',$ts)].'/'.date('m',$ts).'/'.date('Y',$ts);
}

function nombreDia($fecha='')//formato: dd/mm/aaaa
{ 	
	session_start();
	if(isset($_SESSION['idiomaId']))
		$idiomaId=$_SESSION['idiomaId'];
	else
		$idiomaId=1;
		
	$fecha= empty($fecha)?date('d/m/Y'):$fecha;
	if($idiomaId==1)
		$dias = array('domingo','lunes','martes','mi�rcoles','jueves','viernes','s�bado');
	else
		$dias = array('sunday','monday','tuesday','wednesday','thursday','frifay','saturday');
	$dt   = explode(' ',$fecha);
	$dd   = explode('/',$dt);
	$ts   = mktime(0,0,0,$dd[1],$dd[0],$dd[2]);
	return $dias[date('w',$ts)].'/'.date('m',$ts).'/'.date('Y',$ts);
}

function nameMonthYear($fecha) //dd/mm/aaaa
{
	session_start();
	if(isset($_SESSION['idiomaId']))
		$idiomaId=$_SESSION['idiomaId'];
	else
		$idiomaId=1;
		
	list($dia,$mes,$anio)=explode("/",$fecha);
    
	if($idiomaId==1)
	{
		if($mes==1)
			return 'Ene '.$anio;
		if($mes==2)
			return 'Feb '.$anio;
		if($mes==3)
			return 'Mar '.$anio;
		if($mes==4)
			return 'Abr '.$anio;
		if($mes==5)
			return 'May '.$anio;
		if($mes==6)
			return 'Jun '.$anio;
		if($mes==7)
			return 'Jul '.$anio;
		if($mes==8)
			return 'Ago '.$anio;
		if($mes==9)
			return 'Sep '.$anio;
		if($mes==10)
			return 'Oct '.$anio;
		if($mes==11)
			return 'Nov '.$anio;
		if($mes==12)
			return 'Dic '.$anio;
	}
	else
	{
		if($mes==1)
			return 'Jan '.$anio;
		if($mes==2)
			return 'Feb '.$anio;
		if($mes==3)
			return 'Mar '.$anio;
		if($mes==4)
			return 'Apr '.$anio;
		if($mes==5)
			return 'May '.$anio;
		if($mes==6)
			return 'Jun '.$anio;
		if($mes==7)
			return 'Jul '.$anio;
		if($mes==8)
			return 'Aug '.$anio;
		if($mes==9)
			return 'Sep '.$anio;
		if($mes==10)
			return 'Oct '.$anio;
		if($mes==11)
			return 'Nov '.$anio;
		if($mes==12)
			return 'Dec '.$anio;
	}
}

function nameMonthYearWithMonth($mes) //1-12
{	
	session_start();
	if(isset($_SESSION['idiomaId']))
		$idiomaId=$_SESSION['idiomaId'];
	else
		$idiomaId=1;
		
	if($idiomaId==1)
	{
		if($mes==1)
			return 'Enero';
		if($mes==2)
			return 'Febrero';
		if($mes==3)
			return 'Marzo';
		if($mes==4)
			return 'Abril';
		if($mes==5)
			return 'Mayo';
		if($mes==6)
			return 'Junio';
		if($mes==7)
			return 'Julio';
		if($mes==8)
			return 'Agosto';
		if($mes==9)
			return 'Septiembre';
		if($mes==10)
			return 'Octubre';
		if($mes==11)
			return 'Noviembre';
		if($mes==12)
			return 'Diciembre';
	}
	else
	{
		if($mes==1)
			return 'January';
		if($mes==2)
			return 'February';
		if($mes==3)
			return 'March';
		if($mes==4)
			return 'April';
		if($mes==5)
			return 'May';
		if($mes==6)
			return 'June';
		if($mes==7)
			return 'July';
		if($mes==8)
			return 'August';
		if($mes==9)
			return 'September';
		if($mes==10)
			return 'October';
		if($mes==11)
			return 'November';
		if($mes==12)
			return 'December';
	}
}

function traerNomNumDia($fecha)
{
    //list($fec,$time)=explode(" ",$fecha);
    list($a,$m,$d)=explode("-",$fecha);
    //list($h,$mi,$s)=explode(":",$time);
    $dia=nameDate($d.'/'.$m.'/'.$a);
    list($diaTxt,$mes,$anio)=explode("/",$dia);    
    return $diaTxt.", ".$d;
}

function cortaTexto($texto, $num)
{ 
 $txt = (strlen($texto) > $num) ? substr($texto,0,$num)."..." : $texto;
 return $txt;
}

function paginarHosp($pais,$destino,$numRegs,$total_paginas,$pagina,$link,$campo,$valor)
{
    if($numRegs>0)
    {                
        echo "<center>";                
            /*if(($pagina - 1) > 0) 
            {                
                echo "<a id=\"numPag\" href=\"#\" onClick=\"paginar(".($pagina-1).",".$link.",".$path.",".$festivo.",'".$campo."','".$valor."');\">Anterior</a> ";
            }*/
                
            for ($j=1; $j<=$total_paginas; $j++)
            { 
                if ($pagina == $j) 
                    echo "<b>".$pagina."</b> "; 
                else
                    //echo "<a id=\"numPag\" href=\"listaHospedajes.php?idiomaId=".$idiomaId."&paisId=".$paisId."&pais=".$pais."&provincia=".$provincia."&canton=".urlencode($canton)."&atractivo=".urlencode($atractivo)."&pag=".$j."&lin=".$link."&".$campo."=".urlencode($valor)."\">$j</a> ";
		    //echo "<a id=\"numPag\" href=\"paisId/".$paisId."/atractivoId/".$atractivoId."/hospedaje/pagina/".$j."/link/".$link."/".$campo."/".urlencode($valor)."\">".$j."</a> ";
		    echo "<a id=\"numPag\" href=\"".$pais."/".$destino."/hoteles/pagina/".$j."/link/".$link."/".$campo."/".urlencode($valor)."\">".$j."</a> ";
            }
                
            /*if(($pagina + 1)<=$total_paginas)
            {
                echo "<a id=\"numPag\" href=\"#\" onClick=\"paginar(".($pagina+1).",".$link.",".$path.",".$festivo.",'".$campo."','".$valor."');\">Siguiente</a> ";
            }*/
        echo "</center>";
    }
}

function paginarHospCerca($pais,$destino,$numRegs,$total_paginas,$pagina,$link,$campo,$valor,$xRel,$yRel,$nomObj)
{
    if($numRegs>0)
    {                
	echo "<center>";                
        for ($j=1; $j<=$total_paginas; $j++)
        { 
		if ($pagina == $j) 
			echo "<b>".$pagina."</b> "; 
                else
			echo '<a id="numPag" href="hotelesCerca.php?pais='.$pais.'&destino='.$destino.'&pag='.$j.'&lin='.$link.'&'.$campo.'='.urlencode($valor).'&xRel='.$xRel.'&yRel='.$yRel.'&nomObj='.$nomObj.'">'.$j.'</a> ';
	}
        echo "</center>";
    }
}

function paginarActPe($pais,$destino,$numRegs,$total_paginas,$pagina,$link,$campo,$valor)
{
	if($numRegs>0)
	{                
		echo "<center>";                
		/*if(($pagina - 1) > 0) 
		{                
			echo "<a id=\"numPag\" href=\"#\" onClick=\"paginar(".($pagina-1).",".$link.",".$path.",".$festivo.",'".$campo."','".$valor."');\">Anterior</a> ";
		}*/
                
		for ($j=1; $j<=$total_paginas; $j++)
		{ 
			if ($pagina == $j) 
				echo "<b>".$pagina."</b> "; 
			else
				//echo "<a id=\"numPag\" href=\"listaActividadPe.php?idiomaId=".$idiomaId."&paisId=".$paisId."&atractivoId=".$atractivoId."&pag=".$j."&lin=".$link."&".$campo."=".urlencode($valor)."\">".$j."</a> ";
				echo "<a id=\"numPag\" href=\"".$pais."/".$destino."/atractivos/pagina/".$j."/link/".$link."/".$campo."/".urlencode($valor)."\">".$j."</a> ";
		}
                
		/*if(($pagina + 1)<=$total_paginas)
		{
			echo "<a id=\"numPag\" href=\"#\" onClick=\"paginar(".($pagina+1).",".$link.",".$path.",".$festivo.",'".$campo."','".$valor."');\">Siguiente</a> ";
		}*/
		echo "</center>";
	}
}

function paginarActPeCerca($pais,$destino,$numRegs,$total_paginas,$pagina,$link,$campo,$valor,$xRel,$yRel,$nomObj)
{
	if($numRegs>0)
	{                
		echo "<center>";                
		for ($j=1; $j<=$total_paginas; $j++)
		{ 
			if ($pagina == $j) 
				echo "<b>".$pagina."</b> "; 
			else
				echo '<a id="numPag" href="atractivosCerca.php?pais='.$pais.'&destino='.$destino.'&pag='.$j.'&lin='.$link.'&'.$campo.'='.urlencode($valor).'&xRel='.$xRel.'&yRel='.$yRel.'&nomObj='.$nomObj.'">'.$j.'</a> ';
		}
                echo "</center>";
	}
}

function paginarFest($pais,$destino,$numRegs,$total_paginas,$pagina,$link,$campo,$valor)
{
	if($numRegs>0)
	{                
		echo "<center>";                
		/*if(($pagina - 1) > 0) 
		{                
			echo "<a id=\"numPag\" href=\"#\" onClick=\"paginar(".($pagina-1).",".$link.",".$path.",".$festivo.",'".$campo."','".$valor."');\">Anterior</a> ";
		}*/
                
		for ($j=1; $j<=$total_paginas; $j++)
		{ 
			if ($pagina == $j) 
				echo "<b>".$pagina."</b> "; 
			else
				if(trim($campo)!="lugar")
					//echo "<a id=\"numPag\" href=\"listaFestivo.php?idiomaId=".$idiomaId."&paisId=".$paisId."&atractivoId=".$atractivoId."&pag=".$j."&lin=".$link."&".$campo."=".urlencode($valor)."\">".$j."</a> ";
					echo "<a id=\"numPag\" href=\"".$pais."/".$destino."/festivos/pagina/".$j."/link/".$link."/".$campo."/".urlencode($valor)."\">".$j."</a> ";
				else
					echo "<a id=\"numPag\" href=\"festivos.php?pais=".$pais."&destino=".$destino."&pag=".$j."&lin=".$link."&".$campo."=".urlencode($valor)."\">".$j."</a> ";
		}
                
		/*if(($pagina + 1)<=$total_paginas)
		{
			echo "<a id=\"numPag\" href=\"#\" onClick=\"paginar(".($pagina+1).",".$link.",".$path.",".$festivo.",'".$campo."','".$valor."');\">Siguiente</a> ";
		}*/
		echo "</center>";
	}
}

function paginarEven($pais,$destino,$numRegs,$total_paginas,$pagina,$link,$campo,$valor)
{
	if($numRegs>0)
	{                
		echo "<center>";                
		/*if(($pagina - 1) > 0) 
		{                
			echo "<a id=\"numPag\" href=\"#\" onClick=\"paginar(".($pagina-1).",".$link.",".$path.",".$festivo.",'".$campo."','".$valor."');\">Anterior</a> ";
		}*/
                
		for ($j=1; $j<=$total_paginas; $j++)
		{ 
			if ($pagina == $j) 
				echo "<b>".$pagina."</b> "; 
			else
				//echo "<a id=\"numPag\" href=\"listaFestivo.php?idiomaId=".$idiomaId."&paisId=".$paisId."&atractivoId=".$atractivoId."&pag=".$j."&lin=".$link."&".$campo."=".urlencode($valor)."\">".$j."</a> ";
				if(trim($campo)!="dia" && trim($campo)!="lugar")
					echo "<a id=\"numPag\" href=\"".$pais."/".$destino."/eventos/pagina/".$j."/link/".$link."/".$campo."/".urlencode($valor)."\">".$j."</a> ";
				else
					echo "<a id=\"numPag\" href=\"eventos.php?pais=".$pais."&destino=".$destino."&pag=".$j."&lin=".$link."&".$campo."=".urlencode($valor)."\">".$j."</a> ";
		}
                
		/*if(($pagina + 1)<=$total_paginas)
		{
			echo "<a id=\"numPag\" href=\"#\" onClick=\"paginar(".($pagina+1).",".$link.",".$path.",".$festivo.",'".$campo."','".$valor."');\">Siguiente</a> ";
		}*/
		echo "</center>";
	}
}


function verCalificacion($calificacion,$texto,$color)
{
    if(!isset($color))
	$color="white";
    if($calificacion==1)
        echo "<td style='color:grey;background:".$color.";'><img src='./img/1stars.gif' />$texto</td>";
    elseif($calificacion==2)
        echo "<td style='color:grey;background:".$color.";'><img src='./img/2stars.gif' />$texto</td>";
    elseif($calificacion==3)
        echo "<td style='color:grey;background:".$color.";'><img src='./img/3stars.gif' />$texto</td>";
    elseif($calificacion==4)
        echo "<td style='color:grey;background:".$color.";'><img src='./img/4stars.gif' />$texto</td>";
    elseif($calificacion==5)
        echo "<td style='color:grey;background:".$color.";'><img src='./img/5stars.gif' />$texto</td>";
    else
        echo "<td style='color:grey;background:".$color.";'><img src='./img/0stars.gif' />$texto</td>";
}

function verCalificacionColSpan1($calificacion,$texto,$color,$plan)
{
	if($plan=="FULL")
	{ 
		if(!isset($color))
			$color="white";
		if($calificacion==1)
			echo "<td style='border-left:1px solid #D3E9F2;color:grey;background:".$color.";'><img src='./img/1stars.gif' />$texto</td>";
		elseif($calificacion==2)
			echo "<td style='border-left:1px solid #D3E9F2;color:grey;background:".$color.";'><img src='./img/2stars.gif' />$texto</td>";
		elseif($calificacion==3)
			echo "<td style='border-left:1px solid #D3E9F2;color:grey;background:".$color.";'><img src='./img/3stars.gif' />$texto</td>";
		elseif($calificacion==4)
			echo "<td style='border-left:1px solid #D3E9F2;color:grey;background:".$color.";'><img src='./img/4stars.gif' />$texto</td>";
		elseif($calificacion==5)
			echo "<td style='border-left:1px solid #D3E9F2;color:grey;background:".$color.";'><img src='./img/5stars.gif' />$texto</td>";
		else
			echo "<td style='border-left:1px solid #D3E9F2;color:grey;background:".$color.";'><img src='./img/0stars.gif' />$texto</td>";
	}
	else
	{
		if(!isset($color))
			$color="white";
		if($calificacion==1)
			echo "<td style='color:grey;background:".$color.";'><img src='./img/1stars.gif' />$texto</td>";
		elseif($calificacion==2)
			echo "<td style='color:grey;background:".$color.";'><img src='./img/2stars.gif' />$texto</td>";
		elseif($calificacion==3)
			echo "<td style='color:grey;background:".$color.";'><img src='./img/3stars.gif' />$texto</td>";
		elseif($calificacion==4)
			echo "<td style='color:grey;background:".$color.";'><img src='./img/4stars.gif' />$texto</td>";
		elseif($calificacion==5)
			echo "<td style='color:grey;background:".$color.";'><img src='./img/5stars.gif' />$texto</td>";
		else
			echo "<td style='color:grey;background:".$color.";'><img src='./img/0stars.gif' />$texto</td>";
	}
}

function verEstrellas($categoria,$texto,$color,$plan)
{
	if($plan=="FULL")
	{
		if(!isset($color))
			$color="white";
		if($categoria==1)
			//echo "<td style='color:#DF7401; width:100%;'><img class='opaco' src='./img/1stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>"; //--------> class='opaco'
			echo "<td style='border-left:1px solid #D3E9F2;color:#DF7401; width:100%;background:".$color.";'><img src='./img/1stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		elseif($categoria==2)
			echo "<td style='border-left:1px solid #D3E9F2;color:#DF7401; width:100%;background:".$color.";'><img src='./img/2stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		elseif($categoria==3)
			echo "<td style='border-left:1px solid #D3E9F2;color:#DF7401; width:100%;background:".$color.";'><img src='./img/3stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		elseif($categoria==4)
			echo "<td style='border-left:1px solid #D3E9F2;color:#DF7401; width:100%;background:".$color.";'><img src='./img/4stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		elseif($categoria==5)
			echo "<td style='border-left:1px solid #D3E9F2;color:#DF7401; width:100%;background:".$color.";'><img src='./img/5stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		else
			echo "<td style='border-left:1px solid #D3E9F2;color:#DF7401; width:100%;background:".$color.";'><img src='./img/0stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
	}
	else
	{
		if(!isset($color))
			$color="white";
		if($categoria==1)
			//echo "<td style='color:#DF7401; width:100%;'><img class='opaco' src='./img/1stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>"; //--------> class='opaco'
			echo "<td style='color:#DF7401; width:100%;background:".$color.";'><img src='./img/1stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		elseif($categoria==2)
			echo "<td style='color:#DF7401; width:100%;background:".$color.";'><img src='./img/2stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		elseif($categoria==3)
			echo "<td style='color:#DF7401; width:100%;background:".$color.";'><img src='./img/3stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		elseif($categoria==4)
			echo "<td style='color:#DF7401; width:100%;background:".$color.";'><img src='./img/4stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		elseif($categoria==5)
			echo "<td style='color:#DF7401; width:100%;background:".$color.";'><img src='./img/5stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
		else
			echo "<td style='color:#DF7401; width:100%;background:".$color.";'><img src='./img/0stars2.gif' /><font color='grey'>&nbsp;$texto</font></td>";
	}
}

function sumar_dias($fecha,$ndias)
{
	if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
              list($anio,$mes,$dia)=split("/", $fecha);

	if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
              list($anio,$mes,$dia)=split("-",$fecha);
	      
        $nueva = mktime(0,0,0, $mes,$dia,$anio) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("Y-m-d",$nueva);
            
      return ($nuevafecha);  
}

//enviar array entre archivos .php
function enviaArray($array) 
{
     $tmp = serialize($array);
     $tmp = urlencode($tmp);
     return $tmp;
} 

//recibir array entre archivos .php
function recibeArray($array)
{
     $tmp = stripslashes($array);
     $tmp = urldecode($tmp);
     $tmp = unserialize($tmp);
     return $tmp;
}

function errorUrl()
{
	$str = '<a href="http://www.paisturistico.com"><img src="http://www.paisturistico.com/img/pt1.gif" /></a>
	<hr />
	<strong>
	<font face="verdana" color="grey" size="2">Lo sentimos, la direcci�n URL introducida o referenciada no produjo resultados.</font><BR /><BR />
	<font face="verdana" color="grey" size="2">El equipo de </font></font><font face="verdana" color="#DF7401" size="2"><a href="http://www.paisturistico.com">paisturistico.com</a></FONT><BR /><BR />
	</strong>
	<a href="http://www.geotelematica.com"><img style="width:100px;height:64px;" src="http://www.paisturistico.com/img/globo.png" /></a>';
	return $str;
}

/*function generar_codigo($longitud){ 
       $cadena="[^a-zA-Z]"; 
       return substr(eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())), 
       0, $longitud); 
} */
function generar_codigo($longitud)
{
	$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$cad = "";
	for($i=0;$i<$longitud;$i++) 
		$cad .= substr($str,rand(0,62),1);
	return $cad;
}
?>