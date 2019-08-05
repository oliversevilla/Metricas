var VGMsjCarga="<center>Cargando, por favor espere...</center>";
var VGMsjCarga2="Cargando...";
var VGMsjCargandoCliente="<center>Cargando datos del Socio, por favor espere...</center>";
var VGMsjGuardando="<center>Guardando, por favor espere...</center>";
var VGMsjEnviando="<center>Enviando, por favor espere...</center>";
var VGMsjEliminando="<center>Eliminando, por favor espere...</center>";
var VGPesoBanner = 500 //KB
var VGLon=-78.48429; //la carolina
var VGLat=-0.18284; //la carolina
function nuevoAjax()
{ 
  var xmlhttp=false; 
  try { 
   // Creación del objeto ajax para navegadores diferentes a Explorer 
   xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); 
  } catch (e) { 
   // o bien 
   try { 
     // Creación del objet ajax para Explorer 
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");} catch (E) { 
     xmlhttp = false; 
   } 
  } 

  if (!xmlhttp && typeof XMLHttpRequest!='undefined') { 
   xmlhttp = new XMLHttpRequest(); 
  } 
  return xmlhttp; 
} 

function _(id) {
    return document.getElementById(id);
}

function miUnicode(str){
    str = str.replace('á','\u00e1');
    str = str.replace('é','\u00e9');
    str = str.replace('í','\u00ed');
    str = str.replace('ó','\u00f3');
    str = str.replace('ú','\u00fa');
 
    str = str.replace('Á','\u00c1');
    str = str.replace('É','\u00c9');
    str = str.replace('Í','\u00cd');
    str = str.replace('Ó','\u00d3');
    str = str.replace('Ú','\u00da');
 
    str = str.replace('ñ','\u00f1');
    str = str.replace('Ñ','\u00d1');
    
    str = str.replace('¿','\u00bf');
    return str;
}

function soloNumeros(e)
{
    var key;
    if(window.event) // IE
    {
            key = e.keyCode;
    }
    else if(e.which) // Netscape/Firefox/Opera
    {
            key = e.which;
    }

    if ((key < 48 || key > 57) && key!=8)
    {
            return false;
    }

    return true;
}

function soloFloat(e)
{
    var key;
    if(window.event) // IE
    {
        key = e.keyCode;
    }
    else if(e.which) // Netscape/Firefox/Opera
    {
        key = e.which;
    }


    if((key < 48 || key > 57) && key!=46 && key!=8)
    {
        return false;
    }

    return true;
}

function totalIngresos(e)
{
	var ingresos_soci,ingresos_cony,otros_ingr;
		
	if(_("ingresos_soci").value=="") ingresos_soci=0;
	else ingresos_soci = parseInt(_("ingresos_soci").value);
		
	if(_("ingresos_cony").value=="") ingresos_cony=0;
	else ingresos_cony = parseInt(_("ingresos_cony").value);
		
	if(_("otros_ingr").value=="") otros_ingr=0;
	else otros_ingr = parseInt(_("otros_ingr").value);
		
	_("total").value=parseInt(ingresos_soci)+parseInt(ingresos_cony)+parseInt(otros_ingr);
		
}

function emailValido(mail)
{/*
	//var filtro=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+.[A-Za-z0-9_.]+[A-za-z]$/;
        var filtro=/^[A-Za-z0-9_]+.[A-Za-z0-9_.]+[A-za-z]*@[A-Za-z0-9_]+.[A-Za-z0-9_.]+[A-za-z]$/;
	if (mail.length==0)	
		return false;
	if (filtro.test(mail))	
		return true;
	else
		return false;*/
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(mail);
}

function arrayCoordsToJSON(arreglo)
{
	var dataServer = new Array();	
	for(var i in arreglo) {
		var item = arreglo[i];
		dataServer.push({ 
			"id" : item[0],
			"desc"  : item[1]
			});
	}
	//alert(dataServer[0].lon+" "+dataServer[0].lat);
	return dataServer;
}

function arrayDestinoToJSON(arreglo)
{
	var dataServer = new Array();	
	for(var i in arreglo) {
		var item = arreglo[i];
		dataServer.push({ 
			"id" : item[0],
			"desc"  : item[1],
			"region"  : item[2]
			});
	}
	//alert(dataServer[0].lon+" "+dataServer[0].lat);
	return dataServer;
}

function showMsjSinBoton(msj){
    //_("overlay").style.visibility="visible";
    //_("mensaje").style.visibility="visible";
    $("#overlay").css('visibility','visible');
    $("#mensaje").css('visibility','visible')
    //_('msjTxt').innerHTML='<span style="font-size:14px;">'+msj+'</span>';
    $("#msjTxt").html('<span style="font-size:14px;">'+msj+'</span>');
}

function showMsj(msj){
    //_("overlay").style.visibility="visible";
    //_("mensaje").style.visibility="visible";
    $("#overlay").css('visibility','visible');
    $("#mensaje").css('visibility','visible')
    msj += '<br /><button class="btn btn-success btn-flat" onclick="hideMsj();return;">Ok</button><br />';
    //_('msjTxt').innerHTML='<span style="font-size:14px;">'+msj+'</span>';
    $("#msjTxt").html('<span style="font-size:14px;">'+msj+'</span>');
}

function hideMsj(){
    //_("overlay").style.visibility="hidden";
    //_("mensaje").style.visibility="hidden";
    $("#overlay").css('visibility','hidden');
    $("#mensaje").css('visibility','hidden')
}

function showMsjSinOverlay(msj){
    //_("mensaje").style.visibility="visible";
    $("#mensaje").css('visibility','visible')
    msj += '<br /><button class="btn btn-success btn-flat" onclick="hideMsjSinOverlay();return;">Ok</button><br />';
    //_('msjTxt').innerHTML='<span style="font-size:14px;">'+msj+'</span>';
    $("#msjTxt").html('<span style="font-size:14px;">'+msj+'</span>');
}

function hideMsjSinOverlay(){
    //_("mensaje").style.visibility="hidden";
    $("#mensaje").css('visibility','hidden')
}

function showForm(idForm) {
    //_("overlay").style.visibility="visible";
    $("#overlay").css('visibility','visible');
    //_(idForm).style.visibility="visible";
    $("#"+idForm).css('visibility','visible');
    //_(idForm).style.display = 'block';
    $("#"+idForm).css('display','block');
    _(idForm).style.zIndex = 1032;//1000002;
    /*$("#"+idForm).animate({top:"0px", opacity:1}, 100 );*/
}

function hideForm(idForm) {
    //$("#"+idForm).animate({top:"-600px", opacity:1}, 300 ,function() {
        //_(idForm).style.display = 'none';
        $("#"+idForm).css('display','none');
	//_('overlay').style.visibility='hidden';
        $("#overlay").css('visibility','hidden');
        ////_(idForm).style.zIndex = 1;
        ////$("#"+idForm).css("z-index", "1 !important");
    //});
}

function showMsjErr(msj) {
    _("overlay").style.visibility="visible";
    _("msjErr").style.visibility="visible";
    //msj += '<br /><button class="btn btn-success btn-flat" onclick="hideMsjErr();return;">&nbsp;&nbsp;&nbsp;Ok&nbsp;&nbsp;&nbsp;</button><br />';
    _('msjTxtErr').innerHTML='<span style="font-size:14px;">'+msj+'</span>';
    
    /*
    _("overlay").style.visibility="visible";
    _(idForm).style.visibility="visible";
    _(idForm).style.display = 'block';
    _(idForm).style.zIndex = 1032;//1000002;
    var btnCls = '<div id="imgClose" onclick="hideMsj2(\"'+idForm+'\");"><img src="../../../img/eliminar.png" /></div>';
    msj += '<br /><cemter><button class="btn btn-success btn-flat" onclick="hideMsj2(\"'+idForm+'\");return;">&nbsp;&nbsp;&nbsp;&nbsp;Ok&nbsp;&nbsp;&nbsp;&nbsp;</button></center><br />';
    _(idForm).innerHTML='<span style="font-size:14px;">'+btnCls+msj+'</span>';*/
}

function hideMsjErr() {
    //$("#"+idForm).animate({top:"-600px", opacity:1}, 300 ,function() {
        _('msjErr').style.visibility='hidden';
	_('overlay').style.visibility='hidden';
        ////_(idForm).style.zIndex = 1;
        ////$("#"+idForm).css("z-index", "1 !important");
    //});
}

function htmlspecialchars(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

function showMsjInsertUser(msj){
    _("overlay").style.visibility="visible";
    _("mensaje").style.visibility="visible";
    //msj += '<br /><button class="btn btn-primary btn-flat btn-sm" onclick="window.open(\'frmNewUsuario\',\'_self\');return;">Nuevo Usuario</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-default btn-flat btn-sm" onclick="window.open(\'frmListUsuario\',\'_self\');return;">Ir a la Lista de Usuarios</button><br />';
    msj += '<br /><button class="btn btn-default btn-flat btn-sm" onclick="window.open(\'frmNewUsuario\',\'_self\');return;">&nbsp;&nbsp;Nuevo&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-flat btn-sm" onclick="hideMsj();">Continuar</button><br />';
    _('msjTxt').innerHTML=msj;
}

function showMsjInsertWizardEmpresa(msj){
    _("overlay").style.visibility="visible";
    _("mensaje").style.visibility="visible";
    //msj += '<br /><button class="btn btn-default btn-flat btn-sm" onclick="window.open(\'frmNewWizardEmpresa\',\'_self\');return;">Nueva</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-default btn-flat btn-sm" onclick="window.open(\'frmListEmpresa\',\'_self\');return;">Ir a la Lista de Microempresas</button><br />';
    msj += '<br /><button class="btn btn-default btn-flat btn-sm" onclick="window.open(\'frmNewWizardEmpresa\',\'_self\');return;">&nbsp;&nbsp;Nueva&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-flat btn-sm" onclick="window.open(\'frmListEmpresa\',\'_self\');return;">Ir a la Lista</button><br />';
    _('msjTxt').innerHTML=msj;
}

function showMsjInsertEmpresa(msj){
    _("overlay").style.visibility="visible";
    _("mensaje").style.visibility="visible";
    //msj += '<br /><button class="btn btn-primary btn-flat btn-sm" onclick="window.open(\'frmNewEmpresa\',\'_self\');return;">Nueva Microempresa</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-default btn-flat btn-sm" onclick="window.open(\'frmListEmpresa\',\'_self\');return;">Ir a la Lista de Microempresas</button><br />';
    msj += '<br /><button class="btn btn-default btn-flat btn-sm" onclick="window.open(\'frmNewEmpresa\',\'_self\');return;">&nbsp;&nbsp;Nueva&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-flat btn-sm" onclick="hideMsj();">Continuar</button><br />';
    _('msjTxt').innerHTML=msj;
}

function salir()
{       
    var msj;
    /*_("overlay").style.visibility="visible";
    _("mensaje").style.visibility="visible";*/
    $("#overlay").css("visibility","visible");
    $("#mensaje").css("visibility","visible");
    msj = '<span style="font-size:15px;">&iquest;Desea salir de la aplicaci&oacute;n?</span><br /><br /><button class="btn btn-default" onclick="window.location=\'../../../closeSessionAdmin.php\';">&nbsp;&nbsp;&nbsp;Si&nbsp;&nbsp;&nbsp;</button>\n\
		&nbsp;&nbsp;&nbsp;<button class="btn btn-default btn-success" onclick="document.getElementById(\'mensaje\').style.visibility=\'hidden\';document.getElementById(\'overlay\').style.visibility=\'hidden\';">&nbsp;&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;</button>';
    //_('msjTxt').innerHTML=msj;
    $("#msjTxt").html(msj);
}

function salirIndex()
{
   var msj;
    _("overlay").style.visibility="visible";
    _("mensaje").style.visibility="visible";
    msj = '<span style="font-size:15px;">&iquest;Desea salir de la aplicaci&oacute;n?</span><br /><br /><button class="btn btn-default" onclick="window.location=\'../../closeSessionAdmin.php\';">&nbsp;&nbsp;&nbsp;Si&nbsp;&nbsp;&nbsp;</button>\n\
		&nbsp;&nbsp;&nbsp;<button class="btn btn-default btn-success" onclick="document.getElementById(\'mensaje\').style.visibility=\'hidden\';document.getElementById(\'overlay\').style.visibility=\'hidden\';">&nbsp;&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;</button>';
    
    _('msjTxt').innerHTML=msj;
}

//Funcion progressbar cargando
/*
;(function(){
  function id(v){ return document.getElementById(v); }
  function loadbar() {
    var ovrl = id("cargando"),
        prog = id("progress"),
        stat = id("progstat"),
        img = document.images,
        c = 0,
        tot = img.length;
    if(tot == 0) return doneLoading();

    function imgLoaded(){
      c += 1;
      var perc = ((100/tot*c) << 0) +"%";
      prog.style.width = perc;
      stat.innerHTML = "Cargando "+ perc;
      if(c===tot) return doneLoading();
    }
    function doneLoading(){
      ovrl.style.opacity = 0;
      setTimeout(function(){ 
        ovrl.style.display = "none";
      }, 1200);
    }
    for(var i=0; i<tot; i++) {
      var tImg     = new Image();
      tImg.onload  = imgLoaded;
      tImg.onerror = imgLoaded;
      tImg.src     = img[i].src;
    }    
  }
  document.addEventListener('DOMContentLoaded', loadbar, false);
}());*/

function compartir(em_id,em_compartido_tipo){
    var parametros = {
            "em_id" : em_id,
            "em_compartido_tipo": em_compartido_tipo
       };
    jQuery.ajax({
        data:  parametros,
        url:   'admin/php/controlador/empresa/coEmpresaCompartirUpdate.php',
        type:  'post',
        beforeSend: function () {
            //showMsjSinBoton(VGMsjGuardando);
        },
        success:  function (response) {
            //hideMsj();
            //alert(response);
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus+". "+errorThrown);
        }
    });
}