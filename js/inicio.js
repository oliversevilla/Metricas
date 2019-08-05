function init()
{
    //funcionario = new usuario();
    //usuarioImagen = new usuarioFoto();

    //varagencia = new agencia(); 
    
    //vartour = new tour();  
}

function initMeta()
{
    tag = new meta();
}

function initResu()
{
    rptResu = new resu();
}

function initRotef()
{
    anexoRotef = new rotef();
}

function initTasa()
{
    porcentaje = new tasa();
}

function initI01()
{
    inver01 = new i01();
}

function initI02()
{
    inver02 = new i02();
}

function initC02()
{
    carte02 = new c02();
}

function initCliente()
{
    socio = new cliente();
}

function initEmpresa(frontEnd)
{
    negocio = new empresa();
    negocioFoto = new empresaFoto();
    if(frontEnd==undefined) _('banner').addEventListener('change', negocio.setBanner, false);
    
    negocio.setMap();
    negocio.setLonLat();       
    //MAPA para destinos
    var dmapa=negocio.mapa;
    var panZoom = new OpenLayers.Control.PanZoom();
    dmapa.addControl(panZoom);    
    //mostrar imagen "cargando" mapa
    var loadingpanel = new OpenLayers.Control.LoadingPanel();
    dmapa.addControl(loadingpanel);
    //deshabilitar mouse controls para mapa
    var controls = dmapa.getControlsByClass('OpenLayers.Control.Navigation');
    for(var i = 0; i<controls.length; ++i) controls[i].disableZoomWheel();
}

function initUsuario()
{
    empresario = new usuario();
    //_('banner').addEventListener('change', negocio.setBanner, false);
}

function initDestino()
{
    destinofinal = new destino();
    destinoImagen = new destinoFoto();
    destinoFilm = new destinoVideo();
    destinoTradicional = new destinoTradicion();
    destinoGastronomico = new destinoGastronomia();
    destinoLey  = new destinoLeyenda();
    destinoObs  = new destinoObservacion()
    
    destinofinal.setMap();
    destinofinal.setLonLat();
    _('banner').addEventListener('change', destinofinal.setBanner, false);
    
    //MAPA para destinos
    var dmapa=destinofinal.mapa;
    var panZoom = new OpenLayers.Control.PanZoom();
    dmapa.addControl(panZoom);    
    //mostrar imagen "cargando" mapa
    var loadingpanel = new OpenLayers.Control.LoadingPanel();
    dmapa.addControl(loadingpanel);
    //deshabilitar mouse controls para mapa
    var controls = dmapa.getControlsByClass('OpenLayers.Control.Navigation');
    for(var i = 0; i<controls.length; ++i) controls[i].disableZoomWheel();
}

function initHospedaje()
{
    varhospedaje = new hospedaje();
    hospedajeImagen = new hospedajeFoto();
    hospedajeFilm = new hospedajeVideo();
    hospedajeActividad = new hospedajeQueHacer();
    hospedajeOferta = new hospedajeServicio();
    hospedajeCostos = new hospedajeTarifa();
    hospedajeObs = new hospedajeObservacion();
    
    varhospedaje.setMap();
    varhospedaje.setLonLat();
    _('banner').addEventListener('change', varhospedaje.setBanner, false);
    
    //MAPA para destinos
    var dmapa=varhospedaje.mapa;
    var panZoom = new OpenLayers.Control.PanZoom();
    dmapa.addControl(panZoom);    
    //mostrar imagen "cargando" mapa
    var loadingpanel = new OpenLayers.Control.LoadingPanel();
    dmapa.addControl(loadingpanel);
    //deshabilitar mouse controls para mapa
    var controls = dmapa.getControlsByClass('OpenLayers.Control.Navigation');
    for(var i = 0; i<controls.length; ++i) controls[i].disableZoomWheel();
}

function initAtractivo()
{
    atraccion = new atractivo();
    atractivoImagen = new atractivoFoto();
    atractivoFilm = new atractivoVideo();
    atractivoActividad = new atractivoQueHacer();
    atractivoOferta = new atractivoServicio();
    atractivoConsejo = new atractivoRecomendacion();
    
    atraccion.setMap();
    atraccion.setLonLat();
    _('banner').addEventListener('change', atraccion.setBanner, false);
    
    //MAPA para destinos
    var dmapa=atraccion.mapa;
    var panZoom = new OpenLayers.Control.PanZoom();
    dmapa.addControl(panZoom);    
    //mostrar imagen "cargando" mapa
    var loadingpanel = new OpenLayers.Control.LoadingPanel();
    dmapa.addControl(loadingpanel);
    //deshabilitar mouse controls para mapa
    var controls = dmapa.getControlsByClass('OpenLayers.Control.Navigation');
    for(var i = 0; i<controls.length; ++i) controls[i].disableZoomWheel();
}

function initAlimentacion()
{
    varalimentacion = new alimentacion();
    alimentacionImagen = new alimentacionFoto();
    alimentacionFilm = new alimentacionVideo();
    alimentacionPl = new alimentacionPlato();
    alimentacionOferta = new alimentacionServicio();
    alimentacionPromo = new alimentacionPromocion();
    
    varalimentacion.setMap();
    varalimentacion.setLonLat();
    _('banner').addEventListener('change', varalimentacion.setBanner, false);
    
    //MAPA para destinos
    var dmapa=varalimentacion.mapa;
    var panZoom = new OpenLayers.Control.PanZoom();
    dmapa.addControl(panZoom);    
    //mostrar imagen "cargando" mapa
    var loadingpanel = new OpenLayers.Control.LoadingPanel();
    dmapa.addControl(loadingpanel);
    //deshabilitar mouse controls para mapa
    var controls = dmapa.getControlsByClass('OpenLayers.Control.Navigation');
    for(var i = 0; i<controls.length; ++i) controls[i].disableZoomWheel();
}

function initEvento()
{
    varevento = new evento();
    eventoImagen = new eventoFoto();
    eventoFilm = new eventoVideo();
    eventoConsejo = new eventoRecomendacion();
    
    varevento.setMap();
    varevento.setLonLat();
    _('banner').addEventListener('change', varevento.setBanner, false);
    
    //MAPA para destinos
    var dmapa=varevento.mapa;
    var panZoom = new OpenLayers.Control.PanZoom();
    dmapa.addControl(panZoom);    
    //mostrar imagen "cargando" mapa
    var loadingpanel = new OpenLayers.Control.LoadingPanel();
    dmapa.addControl(loadingpanel);
    //deshabilitar mouse controls para mapa
    var controls = dmapa.getControlsByClass('OpenLayers.Control.Navigation');
    for(var i = 0; i<controls.length; ++i) controls[i].disableZoomWheel();
}

function initServicio()
{
    varservicio = new servicio();
    servicioImagen = new servicioFoto();
    servicioFilm = new servicioVideo();
    servicioDet = new servicioDetalle();
    
    varservicio.setMap();
    varservicio.setLonLat();
    _('banner').addEventListener('change', varservicio.setBanner, false);
    
    //MAPA para destinos
    var dmapa=varservicio.mapa;
    var panZoom = new OpenLayers.Control.PanZoom();
    dmapa.addControl(panZoom);    
    //mostrar imagen "cargando" mapa
    var loadingpanel = new OpenLayers.Control.LoadingPanel();
    dmapa.addControl(loadingpanel);
    //deshabilitar mouse controls para mapa
    var controls = dmapa.getControlsByClass('OpenLayers.Control.Navigation');
    for(var i = 0; i<controls.length; ++i) controls[i].disableZoomWheel();
}



function initTour()
{
    vartour = new tour();
    vartourActividad= new touractividad();
    varimagen = new tourFoto();
    vartourIncluye = new tourIncluye();
    vartourItinerario = new touritinerario();
    vartourLugarVisita = new tourlugarvisita();
    vartourObservacion = new tourobservacion();
    vartourPrecio = new tourprecio();
    vartourSalida = new toursalida();
    // tourFilm = new servicioVideo();
    
    /*
    varservicio.setMap();
    varservicio.setLonLat();
    _('banner').addEventListener('change', varservicio.setBanner, false);
    
    //MAPA para destinos
    var dmapa=varservicio.mapa;
    var panZoom = new OpenLayers.Control.PanZoom();
    dmapa.addControl(panZoom);    
    //mostrar imagen "cargando" mapa
    var loadingpanel = new OpenLayers.Control.LoadingPanel();
    dmapa.addControl(loadingpanel);
    //deshabilitar mouse controls para mapa
    var controls = dmapa.getControlsByClass('OpenLayers.Control.Navigation');
    for(var i = 0; i<controls.length; ++i) controls[i].disableZoomWheel();
    */
}


function initAgencia()
{
    varagencia = new agencia();
}

function initTour()
{
    vartour = new tour();
    tourImagen = new tourFoto();    
    /*
    tourDet = new tourDetalle();
    
    vartour.setMap();
    vartour.setLonLat();
    _('banner').addEventListener('change', vartour.setBanner, false);
    
    //MAPA para destinos
    var dmapa=vartour.mapa;
    var panZoom = new OpenLayers.Control.PanZoom();
    dmapa.addControl(panZoom);    
    //mostrar imagen "cargando" mapa
    var loadingpanel = new OpenLayers.Control.LoadingPanel();
    dmapa.addControl(loadingpanel);
    //deshabilitar mouse controls para mapa
    var controls = dmapa.getControlsByClass('OpenLayers.Control.Navigation');
    for(var i = 0; i<controls.length; ++i) controls[i].disableZoomWheel();
     */
}