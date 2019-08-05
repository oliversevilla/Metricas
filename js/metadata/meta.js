var tag; //para instanciar esta clase

function meta()
{
    
}

meta.prototype.uploadURL = function(url){
    var este=this;
    if(url!="")
    {
        var params = {            
            "url": url
        };
        $.ajax({
            data:params,
            url:'../../controlador/meta/coUploadUrl.php',
            type:'POST',
            beforeSend: function () {
                showMsjSinBoton("Subiendo y validando el archivo al servidor... por favor espere<br />");
            },
            success:  function (response) {
                hideMsj();
                if(response>0) {
                    $("#oa_id").val(response);//asignar el oa_id para futuras consultas
                    showMsj("Archivos subidos exitosamente.<br />");
                    $("#subio").css("visibility","visible");                    
                }
                if(response==-1) {
                    showMsj("El archivo de la URL indicada no contiene el est&aacute;ndar Dublin Core ni LOM.<br />");
                    $("#subio").css("visibility","hidden");
                }
                if(response==-2) {
                    showMsj("El archivo de la URL indicada no se pudo subir al servidor.<br />");
                    $("#subio").css("visibility","hidden");
                }
                if(response==-3) {
                    showMsj("No se pudo obtener el &uacute;ltimo Repo/OA ingresado.<br />");
                    $("#subio").css("visibility","hidden");
                }
                if(response==-4) {
                    if (confirm('El documento de la URL ya fué analizado. ¿Desea volver a analizarlo?')) {
                        //este.delete() y este.uploadURL();
                        este.delete(url);
                    }
                    $("#subio").css("visibility","hidden");
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus+". "+errorThrown);
            }
        });
    }
    else {
        showMsj(miUnicode("Ingrese la dirección URL<br />"));    
        $("#subio").css("visibility","hidden");
    }
};

meta.prototype.delete = function(url){
    var este=this;
    if(url!="")
    {
        var params = {            
            "oa_url": url
        };
        $.ajax({
            data:params,
            url:'../../controlador/meta/coDelete.php',
            type:'POST',
            beforeSend: function () {
                showMsjSinBoton("Actualizando el OA... por favor espere<br />");
            },
            success:  function (response) {
                hideMsj();
                if(response>0) {                   
                    este.uploadURL(url);
                }
                else {
                    showMsj("No se pudo actualizar el OA para el nuevo análisis.<br />");
                    $("#subio").css("visibility","hidden");
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus+". "+errorThrown);
            }
        });
    }
    else {
        showMsj(miUnicode("Ingrese la dirección URL<br />"));    
        $("#subio").css("visibility","hidden");
    }
};

meta.prototype.get = function(oa){
    var este=this;
    if(oa>0)
    {
        var params = {            
            "oa_id": oa
        };
        $.ajax({
            data:params,
            url:'../../controlador/meta/coGet.php',
            type:'POST',
            //dataType:'JSON',
            beforeSend: function () {
                showMsjSinBoton("Calculando M&eacute;tricas del OA... por favor espere<br />");
            },
            success:  function (response) {
                hideMsj();
                $("#resultsMeta").css("display","block");
                $("#tblMeta").html("");
                $("#tblMeta").append(response);
                               
                este.graficar($('#totComp').html(),$('#totCons').html(),$('#totCohe').html());
                
                //$("#rptMetricas").css("display","block");
                //$("#rptMetricas").html("hola");//<button class='btn-lg btn btn-success' onclick='meta.get($(\'#oa_id\').val());'>Generar Reporte de Métricas Inconsistentes</button>");
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus+". "+errorThrown);
            }
        });
    }
    else {
        showMsj(miUnicode("Ingrese la dirección URL<br />"));    
        $("#subio").css("visibility","hidden");
    }
};

meta.prototype.graficar = function(comp,cons,cohe){
    var compBarColor='#17BDB8',consBarColor='#17BDB8',coheBarColor='#17BDB8';
    var line1 = [['Completitud', comp],['Consistencia', cons],['Coherencia', cohe]];
        
    //Colores barra Completitud
    if(parseFloat(comp)==1){
        compBarColor='#73C774';//verde
    }
    if(parseFloat(comp)>=0.5 && parseFloat(comp)<1){
        compBarColor='#D9D97E';//amarillo
    }
    if(parseFloat(comp)<0.5){
        compBarColor='#D97E7E';//rojo
    }
    //Colores barra Consistencia
    if(parseFloat(cons)==1){
        consBarColor='#73C774';//verde
    }
    if(parseFloat(cons)>=0.5 && parseFloat(cons)<1){
        consBarColor='#D9D97E';//amarillo
    }
    if(parseFloat(cons)<0.5){
        consBarColor='#D97E7E';//rojo
    }
    //Colores barra Coherencia
    if(parseFloat(cohe)==1){
        coheBarColor='#73C774';//verde
    }
    if(parseFloat(cohe)>=0.5 && parseFloat(cohe)<1){
        coheBarColor='#D9D97E';//amarillo
    }
    if(parseFloat(cohe)<0.5){
        coheBarColor='#D97E7E';//rojo
    }
    
    $('#chart3').jqplot([line1], {
        ////title:'Bar Chart with Custom Colors',
        // Provide a custom seriesColors array to override the default colors.
        seriesColors:[compBarColor, consBarColor, coheBarColor],
        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            rendererOptions: {
                // Set varyBarColor to tru to use the custom colors on the bars.
                varyBarColor: true
            }
        },
        axes:{
            xaxis:{
                renderer: $.jqplot.CategoryAxisRenderer
            },
            yaxis: {
                min:0,
                max:1.0
            }
        },
        highlighter:{
            show:true,
            tooltipContentEditor:tooltipContentEditor
        },
    });
};

meta.prototype.rpt = function(oa){
    var este=this;
    if(oa>0)
    {
        var params = {            
            "oa_id": oa
        };
        $.ajax({
            data:params,
            url:'../../controlador/meta/coRpt.php',
            type:'POST',
            //dataType:'JSON',
            beforeSend: function () {
                showMsjSinBoton("Generando Reporte de M&eacute;tricas del OA... por favor espere<br />");
            },
            success:  function (response) {
                hideMsj();
                este.hash(response);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus+". "+errorThrown);
            }
        });
    }
    else {
        showMsj(miUnicode("Ingrese la dirección URL<br />"));    
        $("#subio").css("visibility","hidden");
    }
};

function tooltipContentEditor(str, seriesIndex, pointIndex, plot) {
    // display series_label, x-axis_tick, y-axis value
    return plot.series[seriesIndex]["label"] + ", " + plot.data[seriesIndex][pointIndex];
}





meta.prototype.hash= function(str)
{      
    this.descargarArchivo(new Blob([str.toString()], {type: 'text/plain'}), 'ReporteMetricas.txt');    
}

meta.prototype.descargarArchivo= function(contenidoEnBlob, nombreArchivo)
{
    var reader = new FileReader();
    reader.onload = function (event) {
        var save = document.createElement('a');
        save.href = event.target.result;
        save.target = '_blank';
        save.download = nombreArchivo || 'archivo.dat';
        var clicEvent = new MouseEvent('click', {
            'view': window,
            'bubbles': true,
            'cancelable': true
        });
        save.dispatchEvent(clicEvent);
        (window.URL || window.webkitURL).revokeObjectURL(save.href);
    };
    reader.readAsDataURL(contenidoEnBlob);
};

meta.prototype.validateUploadFileTXT = function(){
    if($("#archivoCli").val()!="")
    {
        ////var archivo = $("#archivo").val();
        var archivoCli = $("#archivoCli").val();
        var exts = new Array(".txt"); 
        //var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();	
        var extensionCli = (archivoCli.substring(archivoCli.lastIndexOf("."))).toLowerCase();
        var filePermitido = false;
        for (var i = 0; i < exts.length; i++) 
        {
            //if (exts[i] == extension)
            if (exts[i] == extensionCli)
            {
                filePermitido = true;
                var inputFileImageCli = _("archivoCli");
                var fileCli = inputFileImageCli.files[0];
                var data = new FormData();
                data.append("fileCli",fileCli);
                return $.ajax({
                    data:data,
                    url:'../../controlador/meta/coUploadFile.php',
                    type:'POST',
                    contentType:false,
                    processData:false,
                    cache:false,
                    beforeSend: function () {
                        showMsjSinBoton("Subiendo el archivo al servidor... por favor espere<br />");
                    },
                    success:  function (response) {
                        hideMsj();
                        if(response==0) {
                            showMsj("No se pudo subir el archivo TXT, trate de nuevo.<br />");
                            $("#subio").css("visibility","hidden");
                        }
                        else{ 
                            showMsj("Archivo subido exitosamente.<br />");
                            $("#subio").css("visibility","visible");//visto en FE                        
                        }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus+". "+errorThrown);
                    }
                });
                break;
            }
        }
        if(!filePermitido)
        {
            showMsj(miUnicode("El archivo tiene una extensión no permitida, solo se permite .txt <br />"));
            $("#subio").css("visibility","hidden");
        }
    }
    else {
        showMsj(miUnicode("El archivo TXT no han sido seleccionado (.txt)<br />"));    
        $("#subio").css("visibility","hidden");
    }
};

meta.prototype.calcularTXT = function(){
    if($("#archivoCli").val()!="")
    {
        if($("#subio").css("visibility")=="visible")
        {
            var inputFileImageCli = _("archivoCli");
            var fileCli = inputFileImageCli.files[0];
            var data = new FormData();
            data.append("fileCli",fileCli);
            return $.ajax({
                data:data,
                url:'../../controlador/meta/coUploadUrlBdd.php',
                type:'POST',
                contentType:false,
                processData:false,
                cache:false,
                beforeSend: function () {
                    showMsjSinBoton("Calculando m&eacute;tricas... por favor espere<br />");
                },
                success:  function (response) {
                    hideMsj();
                    $("#resultsMeta").css("display","block");
                    $("#tblMeta").html("");
                    $("#tblMeta").append(response);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus+". "+errorThrown);
                }
            });
        }
        else {
            showMsj(miUnicode("El archivo TXT aún no han sido subido al servidor<br />"));    
            $("#subio").css("visibility","hidden");
        }        
    }    
    else {
        showMsj(miUnicode("El archivo TXT no han sido seleccionado (.txt)<br />"));    
        $("#subio").css("visibility","hidden");
    }        
};