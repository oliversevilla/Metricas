var meta; //para instanciar esta clase

function metadata()
{
    
}

metadata.prototype.uploadURL = function(url){
    if(url!="")
    {
        var params = {            
            "url": url
        };
        $.ajax({
            data:params,
            url:'../../controlador/metadata/coUploadUrl.php',
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
                    showMsj("No se pudo obtener el &uacute;ltimo OA ingresado.<br />");
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

metadata.prototype.get = function(oa){
    var este=this;
    if(oa>0)
    {
        var params = {            
            "oa": oa
        };
        $.ajax({
            data:params,
            url:'../../controlador/metadata/coGet.php',
            type:'POST',
            //dataType:'JSON',
            beforeSend: function () {
                showMsjSinBoton("Calculando M&eacute;tricas del OA... por favor espere<br />");
            },
            success:  function (response) {
                hideMsj();
                $("#resultsMeta").css("display","block");
                $("#tblMetadata").html("");
                $("#tblMetadata").append(response);
                               
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

metadata.prototype.graficar = function(comp,cons,cohe){
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

metadata.prototype.rpt = function(oa){
    var este=this;
    if(oa>0)
    {
        var params = {            
            "oa": oa
        };
        $.ajax({
            data:params,
            url:'../../controlador/metadata/coRpt.php',
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





metadata.prototype.hash= function(str)
{      
    this.descargarArchivo(new Blob([str.toString()], {type: 'text/plain'}), 'ReporteMetricas.txt');    
}

metadata.prototype.descargarArchivo= function(contenidoEnBlob, nombreArchivo)
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