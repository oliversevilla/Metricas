var tag; //para instanciar esta clase
//constructor
function meta()
{
    
}

//metodos

//subir archivo al servidor
meta.prototype.uploadURL = function(url){
    var este=this;
    url=$.trim(url);
    url=url.replace(/\t+/g,'');
    if(url!="")
    {
        var params = {            
            "url": $.trim(url)
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
                    $("#btnCalMetNew").removeClass("disabled");
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

//borrar metas
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

//pobtener metas
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
    /****else {
        showMsj(miUnicode("Ingrese la dirección URL<br />"));    
        $("#subio").css("visibility","hidden");
    }****/
};

//graficar metas
meta.prototype.graficar = function(comp,cons,cohe){
    
    /*
    var s1 = [comp,cons,cohe]; 
    var ticks = ['COMPLETITUD '+comp, 'CONSISTENCIA'+cons, 'COHERENCIA'+cohe]; 
    plot2 = $.jqplot('chart3', [s1,[],[]], { //give it extra blank series
        seriesDefaults: { 
           renderer: $.jqplot.BarRenderer, 
           rendererOptions: { varyBarColor : true }, 
           pointLabels: { show: true }, 
           showLabel: true
         }, 
         series: [ {}, 
                   {renderer: $.jqplot.LineRenderer}, // set our empty series to the lineRenderer, so the bar plot isn't padded for room
                   {renderer: $.jqplot.LineRenderer},
                   {renderer: $.jqplot.LineRenderer} ], 
         //legend: { show: true, placement: 'outside', labels: ticks},  // give the legend the tick labels
         axes:   { xaxis: { renderer: $.jqplot.CategoryAxisRenderer, ticks: ticks }}, 
         yaxis:  {  tickOptions:{ formatString:'%.2f%' } }
    });
    return;*/
    
    
    var compBarColor='#17BDB8',consBarColor='#17BDB8',coheBarColor='#17BDB8';
    var line1 = [['Completitud '+parseFloat(comp).toFixed(2), comp],['Consistencia '+parseFloat(cons).toFixed(2), cons],['Coherencia '+parseFloat(cohe).toFixed(2), cohe]];
        
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
    /*
    $('#chart3').jqplot([line1], {
        //title: 'Bar Chart with Point Labels', 
        seriesColors:[compBarColor, consBarColor, coheBarColor],
        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            pointLabels: { show: true,labels:['fourteen', 'thirty two', 'fourty one']},
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
                max:1.0,
                padMax:1.3
            }
        },
        highlighter:{
            show:false,
            tooltipContentEditor:tooltipContentEditor
        }
    });
    */
    var s1 = [parseFloat(comp),parseFloat(cons),parseFloat(cohe)];
    var ticks = ['COMPLETITUD '+parseFloat(comp).toFixed(2), 'CONSISTENCIA '+parseFloat(cons).toFixed(2), 'COHERENCIA '+parseFloat(cohe).toFixed(2)]; 
    plot2 = $.jqplot('chart3', [s1,[],[]], { //give it extra blank series
    //$('#chart3').jqplot([line1], {
        //title: 'Bar Chart with Point Labels', 
        //seriesColors:[compBarColor, consBarColor, coheBarColor],
        seriesDefaults: { 
           renderer: $.jqplot.BarRenderer, 
           rendererOptions: { varyBarColor : true }, 
           pointLabels: { show: true }, 
           showLabel: true
         }, 
         series: [ {}, 
                   {renderer: $.jqplot.LineRenderer}, // set our empty series to the lineRenderer, so the bar plot isn't padded for room
                   {renderer: $.jqplot.LineRenderer},
                   {renderer: $.jqplot.LineRenderer} ], 
         //legend: { show: true, placement: 'outside', labels: ticks},  // give the legend the tick labels
         axes:   { xaxis: { renderer: $.jqplot.CategoryAxisRenderer, ticks: ticks }}, 
         yaxis:  {  tickOptions:{ formatString:'%.2f%' } }
    });
};

//generar reporte de metas
meta.prototype.rpt = function(oa,ext){
    var este=this,vUrl='';
    if(ext=='txt') vUrl='../../controlador/meta/coRptTxt.php';
    if(ext=='pdf') vUrl='../../controlador/meta/coRptPdf.php';
    if(oa>0)
    {
        var params = {            
            "oa_id": oa
        };
        $.ajax({
            data:params,
            url:vUrl,
            type:'POST',
            //dataType:'JSON',
            beforeSend: function () {
                showMsjSinBoton("Generando Reporte de M&eacute;tricas del OA... por favor espere<br />");
            },
            success:  function (response) {
                hideMsj();                
                if(ext=='txt') este.txt(response);
                if(ext=='pdf') este.pdf(response);
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

//generar reporte de metas dado el repositorio
meta.prototype.rptRepo = function(re_id){
    
    var table1 = tableToJson($('#datatable').get(0)),
        cellWidth = 35,rowCount = -1,cellContents,leftMargin = 2,anchoCol=0,
        topMargin0 = 12, topMargin1 = 22,topMargin2 = 52, topMarginTable = 55,headerRowHeight = 18,
        rowHeight = 15,
        l = {
            orientation: 'l',unit: 'mm',format: 'a3',
            compress: true,fontSize: 8,lineHeight: 1,
            autoSize: true,printHeaders: true
        };
    
    //crear objeto PDF
    var doc = new jsPDF(l, '', '', '');
    doc.setFontSize(22);
    doc.setFontType('bold')
    doc.text(2, topMargin0, miUnicode('Reporte de OAs por Repositorio'));
    
    doc.setProperties({
        title: 'Reporte de Repositorio',subject: 'Reporte de Repositorio y sus Metricas',
        author: 'Oliver Sevilla',keywords: 'reporte repositorio metricas',
        creator: 'Oliver Sevilla'
    });

    doc.cellInitialize();
    
    //imprimircabecera de la tabla
    doc.margins = 1;
    doc.setFont("helvetica");
    doc.setFontType("bold");
    doc.setFontSize(12);
    doc.cell(leftMargin, topMargin1, 9, headerRowHeight, "#", -1);
    doc.cell(leftMargin, topMargin1, 57, headerRowHeight, "REPOSITORIO", -1);
    doc.cell(leftMargin, topMargin1, 100, headerRowHeight, "TITULO", -1);
    doc.cell(leftMargin, topMargin1, 100, headerRowHeight, "KEYWORDS", -1);
    doc.cell(leftMargin, topMargin1, 34, headerRowHeight, "ESTANDAR", -1);
    doc.cell(leftMargin, topMargin1, 100, headerRowHeight, "RESULTADOSR", -1);
    
    $.each(table1, function (i, row)
    {
        //imprimir cada fila
        rowCount++;
        $.each(row, function (j, cellContent) {
            //definir ancho de columna segun corresponda
            anchoCol=100;
            if(j=='#') anchoCol=9;
            if(j=='repositorio') anchoCol=57;
            if(j==miUnicode('estándar')) anchoCol=34;
            //imprimir columnas
            doc.margins = 1;
            doc.setFont("courier ");
            doc.setFontType("normal ");
            doc.setFontSize(11);
            doc.cell(leftMargin, topMargin2, anchoCol, rowHeight, cellContent, i);
        })
    })
    doc.save('Reporte de OAs por Repositorio.pdf');
};

//cambiar tootltiptext al pasar mouse sobre las graficas
function tooltipContentEditor(str, seriesIndex, pointIndex, plot) {  
    return plot.series[seriesIndex]["label"] + ", " + plot.data[seriesIndex][pointIndex];
}

function tableToJson(table) {
    var data = [];

    // first row needs to be headers
    var headers = [];
    for (var i=0; i<table.rows[0].cells.length; i++) {
        headers[i] = table.rows[0].cells[i].innerHTML.toLowerCase().replace(/ /gi,'');
    }

    // go through cells
    for (var i=1; i<table.rows.length; i++) {
        var tableRow = table.rows[i];
        var rowData = {};
        for (var j=0; j<tableRow.cells.length; j++) {
            rowData[ headers[j] ] = tableRow.cells[j].innerHTML;
        }
        data.push(rowData);
    }       
    return data;
}

//descarga de archivos reportes
//descarga de archivos reportes
meta.prototype.pdf= function(str)
{     
    var pageStart = 20;
    var step = 5;
    var line = 30;
    
    var pdf = new jsPDF();
    pdf.setFontSize(22);
    pdf.setFontType('bold')
    pdf.text(20, 20, miUnicode('Reporte de Métricas Inconsistentes'));
    pdf.setFontType('normal')
    pdf.setFontSize(12);
    
    //imprimir str con multilinea si se requiere
    var splitTitle = pdf.splitTextToSize(str, 180);
    for (var i = 0; i < splitTitle.length; i++) {
        pdf.text(splitTitle[i], 20, line);
        if (line >= 275) {
            pdf.addPage();
            line = pageStart;
        }
        line = line + step;
    }
    
    //pdf.text(20,30,str);
    pdf.setFontSize(10);
    pdf.setFont('times')
    pdf.setFontType('italic')
    pdf.setTextColor(255,0,0);
    pdf.text(20,pdf.internal.pageSize.height - 10,'Por problemas de compatibilidad con el navegador se pueden omitir caracteres especiales como tildes');
    pdf.save('Reporte de Análisis.pdf');
    
    /*html2canvas(document.body,{
        onrendered:function(canvas){
            var img=canvas.toDataURL("image/png");
            var doc = new jsPDF();
            doc.addImage(img,'JPEG',20,20);
            doc.save('test.pdf');
        }
    });*/
    
    ////this.descargarArchivo(new Blob([str.toString()], {type: 'application/pdf'}), 'ReporteMetricas.pdf');    
}

meta.prototype.txt= function(str)
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

//validar extension de archivo antes de subir al servidor
meta.prototype.validateUploadFileTXT = function(){
    if($("#archivoCli").val()!="")
    {
        var archivoCli = $("#archivoCli").val();
        var exts = new Array(".txt"); 
        var extensionCli = (archivoCli.substring(archivoCli.lastIndexOf("."))).toLowerCase();
        var filePermitido = false;
        for (var i = 0; i < exts.length; i++) 
        {
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
                            $("#btnCalMetBdd").removeClass("disabled");
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
//subir archivo base de datos y calcular metricas
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
        /****else {
            showMsj(miUnicode("El archivo TXT aún no han sido subido al servidor<br />"));    
            $("#subio").css("visibility","hidden");
        }****/
    }    
    /****else {
        showMsj(miUnicode("El archivo TXT no han sido seleccionado (.txt)<br />"));    
        $("#subio").css("visibility","hidden");
    }****/    
};

meta.prototype.changeRepo= function()
{
    window.location.href='frmRptOA.php?re_id='+$("#re_id").val();
};