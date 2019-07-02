/********************************
Clase Usuario
********************************/

var empresario;

function usuario()
{

}

usuario.prototype.insert = function()
{
    var valido=true;   
    
    if($("#us_nombre").val()==""){
        $("#us_nombre").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else $("#us_nombre").css("border","1px solid #CCC");
    
    if($("#us_apellido").val()==""){        
        $("#us_apellido").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else $("#us_apellido").css("border","1px solid #CCC");
    
    if($("#us_mail").val()==""){
        showMsj(miUnicode("Ingresa el E-mail del Usuario<br />"));
        $("#us_mail").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else {
        if(emailValido($("#us_mail").val()))
            $("#us_mail").css("border","1px solid #CCC");
        else {
            showMsj(miUnicode("Ingresa un E-mail válido<br />"));
            $("#us_mail").css("border","1px solid #FF3130");
            valido=false;
            return 0;
        }
    }
    
    if($("#us_clave").val()==""){
        showMsj(miUnicode("Ingresa la Clave<br />"));
        $("#us_clave").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else $("#us_clave").css("border","1px solid #CCC");
    
    if($("#us_clave2").val()==""){
        showMsj(miUnicode("Repite la Clave<br />"));
        $("#us_clave2").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else $("#us_clave2").css("border","1px solid #CCC");
    
    if($.trim($("#us_clave").val())!=$.trim($("#us_clave2").val())){
        showMsj(miUnicode("Las Claves no coinciden<br />"));
        $("#us_clave").css("border","1px solid #FF3130");
        $("#us_clave2").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else {
        $("#us_clave").css("border","1px solid #CCC"); 
        $("#us_clave2").css("border","1px solid #CCC");
    }
    
    if(valido){
        var parametros = {
            "us_nombre" : $("#us_nombre").val(),
            "us_apellido" : $("#us_apellido").val(),
            "us_mail" : $("#us_mail").val(),
            "us_clave" : $('#us_clave').val()
       };
        return $.ajax({
            data: parametros,
            url:   '../../controlador/usuario/coUsuarioInsert.php',
            type:  'post',
            async: false, //<----clave para q funcion ajax retorne un valor
            beforeSend: function () {
                showMsjSinBoton(VGMsjGuardando);
            },
            success:  function (response) {
                hideMsj();
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus+". "+errorThrown);            
           }
        });
    }
};
/*
usuario.prototype.delete = function(us_id)
{
    //document.getElementById("op_tipo_solicitud").value;
    var parametros = {

        "us_id" : us_id
   };
    $.ajax({
        data: parametros,
        url:   '../../controlador/usuario/coUsuarioDelete.php',
        type:  'post',
        beforeSend: function () {
            //pieI.innerHTML=VGMsjCarga;
        },
        success:  function (response) {
            //pieI.innerHTML = response;                        
            if(response!=1) showMsj(response);
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus+". "+errorThrown);
            return 0;
       }
    });
};
*/

usuario.prototype.update= function(us_id)
{
    var valido=true;   
    
    if($("#us_nombre").val()==""){
        $("#us_nombre").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else $("#us_nombre").css("border","1px solid #CCC");
    
    if($("#us_apellido").val()==""){        
        $("#us_apellido").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else $("#us_apellido").css("border","1px solid #CCC");
    
    if($("#us_mail").val()==""){
        showMsj(miUnicode("Ingrese el E-mail del Usuario<br />"));
        $("#us_mail").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else {
        if(emailValido($("#us_mail").val()))
            $("#us_mail").css("border","1px solid #CCC");
        else {
            showMsj(miUnicode("Ingrese un E-mail válido<br />"));
            $("#us_mail").css("border","1px solid #FF3130");
            valido=false;
            return 0;
        }
    }
    
    if($("#us_clave").val()==""){
        showMsj(miUnicode("Ingrese la Clave<br />"));
        $("#us_clave").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else $("#us_clave").css("border","1px solid #CCC");
    
    if($("#us_clave2").val()==""){
        showMsj(miUnicode("Repita la Clave<br />"));
        $("#us_clave2").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else $("#us_clave2").css("border","1px solid #CCC");
    
    if($.trim($("#us_clave").val())!=$.trim($("#us_clave2").val())){
        showMsj(miUnicode("Las Claves no coinciden<br />"));
        $("#us_clave").css("border","1px solid #FF3130");
        $("#us_clave2").css("border","1px solid #FF3130");
        valido=false;
        return 0;
    } else {
        $("#us_clave").css("border","1px solid #CCC"); 
        $("#us_clave2").css("border","1px solid #CCC");
    }
    
    if(valido){
        var parametros = {
            "us_id" : us_id,
            "us_nombre" : $("#us_nombre").val(),
            "us_apellido" : $("#us_apellido").val(),
            "us_mail" : $("#us_mail").val(),            
            "us_clave" : $('#us_clave').val(),
            "us_estado" : $("#us_estado").val(),
        };
        return $.ajax({
            data: parametros,
            url:   '../../controlador/usuario/coUsuarioUpdate.php',
            type:  'post',
            beforeSend: function () {
                showMsjSinBoton(VGMsjGuardando);
            },
            success:  function (response) {
                hideMsj();
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus+". "+errorThrown);
            }
        });
    }
};

usuario.prototype.changeEstado = function(evt){    
    if($("#us_estado").is(':checked')){
        $("#txt_us_estado").html("ACTIVADO");
        $("#us_estado").val("ACTIV");
    }
    else {
        $("#txt_us_estado").html("DESACTIVADO");
        $("#us_estado").val("INACT");
    }
};
/*
usuario.prototype.formNewFoto= function()
{
    var parametros = {
        "us_id" : $("#us_id").val()
    };
    $.ajax({
        data: parametros,
        url:   'php/vista/usuario/formNewUsuarioFoto.php',
        type:  'post',
        beforeSend: function () {
            showMsj(VGMsjCarga);
        },
        success:  function (response) {
            hideMsj();            
            $("#contenedor").html(response);
            //Formas para crear nuevos detalles
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus+". "+errorThrown);
        }
    });
};
*/