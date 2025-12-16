@extends('backend.layouts.master')

@section('title')
    {{ __('Reportes - Panel de Reporte') }}
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">

    <style>
        #overlay{	
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height:100%;
            display: none;
            background: rgba(0,0,0,0.6);
        }
        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;  
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }
        @keyframes sp-anime {
            100% { 
                transform: rotate(360deg); 
            }
        }
        .is-hide{
            display:none;
        }
    </style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">{{ __('Reportes') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">

    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                @include('backend.layouts.partials.messages')
                <div class="accordion" id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Búsqueda
                                </button>
                            </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form action="" method="POST" id="reporte">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="proceso_id_search">Seleccione un Proceso:</label>
                                                <select id="proceso_id_search" name="tipo_reporte_search" class="form-control selectpicker" data-live-search="true" required>
                                                    <option value="">Seleccione un Proceso</option>    
                                                    @foreach ($procesos as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_reporte_search">Seleccione un Reporte:</label>
                                                <select id="tipo_reporte_search" name="tipo_reporte_search" class="form-control selectpicker" data-live-search="true" required>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="campo_search">Seleccione los campos para el filtro:</label>
                                                <select id="campo_search" name="campo_search" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div id="filtros"></div>
                                        @if (auth()->user()->can('reporteTramites.download'))
                                        <div class="col-sm-2">
                                            <button id="generarReporte" type="button" class="btn btn-success mt-4 pr-4 pl-4">Generar Reporte</button>
                                        </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
     <!-- Start datatable js -->
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
     
     <script>
        let table = "";
        let tableRef = "";
        let tableHeaderRef = "";
        let tiposReporte = [];
        let listaCampos = [];
        let campos = [];
        let camposPorSeccion = [];

        $(document).ready(function() {

            $.fn.datepicker.dates['es'] = {
                days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                daysShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                daysMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthsShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                today: 'Hoy',
                clear: 'Limpiar',
                format: 'yyyy-mm-dd',
                titleFormat: "MM yyyy", 
                weekStart: 1
            };

            //renderFilterForm();

            $('.datepicker').datepicker({
                language: 'es',
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
                endDate: 0
            });

            $( "#proceso_id_search" ).on( "change", function() {
                $.ajax({
                    url: "{{url('/getTiposReporteByProcesoId')}}",
                    method: "POST",
                    data: {
                        proceso_id_search: $('#proceso_id_search').val(),
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        $("#overlay").fadeOut(300);

                        tiposReporte = response.tiposReporte;
                        listaCampos = response.listaCampos;

                        $('#campo_search').selectpicker('destroy');
                        $("#campo_search").html('');
                        $('#campo_search').selectpicker();
                        $('#tipo_reporte_search').selectpicker('destroy');
                        $("#tipo_reporte_search").html('');
                        $("#tipo_reporte_search").append('<option value="">Seleccione un Reporte</option>');
                        $.each(tiposReporte, function (key, value) {
                            $("#tipo_reporte_search").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                        $('#tipo_reporte_search').selectpicker();
                        $('.selectpicker').selectpicker('refresh');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.status);
                        console.log(error);
                        $('#tipo_reporte_search').selectpicker('destroy');
                        $("#tipo_reporte_search").html('');
                        $('#tipo_reporte_search').selectpicker();
                        $('#campo_search').selectpicker('destroy');
                        $("#campo_search").html('');
                        $('#campo_search').selectpicker();
                    }
                });
            });

            $( "#tipo_reporte_search" ).on( "change", function() {
                $.ajax({
                    url: "{{url('/getCamposByTipoReporte')}}",
                    method: "POST",
                    data: {
                        proceso_id_search: $('#proceso_id_search').val(),
                        tipo_reporte_search: $('#tipo_reporte_search').val(),
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        $("#overlay").fadeOut(300);

                        campos = response.campos;

                        $('#campo_search').selectpicker('destroy');
                        $("#campo_search").html('');
                        $("#campo_search").append('<option value="">Seleccione los campos para el filtro</option>');
                        $.each(campos, function (key, value) {
                            $("#campo_search").append('<option value="' + value.nombre_seccion + '-' + value.campo + '">' + value.nombre_seccion + '-' + value.nombre_campo + '</option>');
                        });
                        $('#campo_search').selectpicker();
                        $('.selectpicker').selectpicker('refresh');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.status);
                        console.log(error);
                        $('#campo_search').selectpicker('destroy');
                        $("#campo_search").html('');
                        $('#campo_search').selectpicker();
                    }
                });
            });

            $( "#campo_search" ).on( "change", function() {
                console.log('campo_search change');
                console.log($(this).val());
                renderFilterForm($(this).val());
            });

            $( "#generarReporte" ).on( "click", function() {
                if(document.getElementById('reporte').reportValidity()){
                    $("#overlay").fadeIn(300);
                    $.ajax({
                        url: "{{url('/generarReporteByTipoReporte')}}",
                        method: "POST",
                        data: {
                            tipo_tramite_search: JSON.stringify($('#tipo_tramite_search').val()),
                            tipo_reporte_search: $('#tipo_reporte_search').val(),
                            fecha_registro_desde_search: $('#fecha_registro_desde_search').val(),
                            fecha_registro_hasta_search: $('#fecha_registro_hasta_search').val(),
                            tipo_id_search: JSON.stringify($('#tipo_id_search').val()),
                            ruc_search: $('#ruc_search').val(),
                            numero_establecimiento_search: $('#numero_establecimiento_search').val(),
                            razon_social_search: $('#razon_social_search').val(),
                            fecha_recepcion_desde_search: $('#fecha_recepcion_desde_search').val(),
                            fecha_recepcion_hasta_search: $('#fecha_recepcion_hasta_search').val(),
                            tipo_atencion_id_search: JSON.stringify($('#tipo_atencion_id_search').val()),
                            provincia_id_search: JSON.stringify($('#provincia_id_search').val()),
                            fecha_servicio_desde_search: $('#fecha_servicio_desde_search').val(),
                            fecha_servicio_hasta_search: $('#fecha_servicio_hasta_search').val(),
                            numero_casos_search: $('#numero_casos_search').val(),
                            monto_planilla_search: $('#monto_planilla_search').val(),
                            numero_caja_ant_search: $('#numero_caja_ant_search').val(),
                            numero_caja_search: $('#numero_caja_search').val(),
                            tipo_estado_caja_id_search: JSON.stringify($('#tipo_estado_caja_id_search').val()),
                            numero_caja_auditoria_search: $('#numero_caja_auditoria_search').val(),
                            fecha_envio_auditoria_desde_search: $('#fecha_envio_auditoria_desde_search').val(),
                            fecha_envio_auditoria_hasta_search: $('#fecha_envio_auditoria_hasta_search').val(),
                            institucion_id_search: JSON.stringify($('#institucion_id_search').val()),
                            documento_externo_search: $('#documento_externo_search').val(),
                            tipo_firma_search: JSON.stringify($('#tipo_firma_search').val()),
                            observacion_search: $('#observacion_search').val(),
                            numero_quipux_search: $('#numero_quipux_search').val(),
                            periodo_search: $('#periodo_search').val(),
                            estado_tramite_id_search: JSON.stringify($('#estado_tramite_id_search').val()),
                            fecha_devolucion_auditoria_desde_search: $('#fecha_devolucion_auditoria_desde_search').val(),
                            fecha_devolucion_auditoria_hasta_search: $('#fecha_devolucion_auditoria_hasta_search').val(),
                            fecha_devolucion_prestador_desde_search: $('#fecha_devolucion_prestador_desde_search').val(),
                            fecha_devolucion_prestador_hasta_search: $('#fecha_devolucion_prestador_hasta_search').val(),
                            observacion_devolucion_auditoria_search: $('#observacion_devolucion_auditoria_search').val(),
                            observacion_devolucion_prestador_search: $('#observacion_devolucion_prestador_search').val(),
                            responsable_id_search: JSON.stringify($('#responsable_id_search').val()),
                            _token: '{{csrf_token()}}'
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function (response) {
                            $("#overlay").fadeOut(300);

                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(response);
                            a.href = url;
                            a.download = 'reporte.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                        }
                    });
                }
            });

            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd"
            });

        });

        function renderFilterForm(campos_seleccionados){
            let html_components = "";
            let long = campos_seleccionados.length;
            

            camposPorSeccion = Object.groupBy(listaCampos, (campo) => campo.seccion_campo);
            console.log(camposPorSeccion);
            
            for (let campo in campos_seleccionados) {
            
                let count = 1;
                let seccion = '';

                let obj = campos_seleccionados[campo].split('-');
                seccion = obj[0];

                html_components += '<div class="form-row">';

                html_components += contruirFiltros(count,long,seccion);

                html_components += '</div>';
            }

            $("#filtros").append(html_components);
        }

        function contruirFiltros(count,long,seccion){
            let html_components = '';
            for (let campo of camposPorSeccion[seccion]) {
                switch (campo.tipo_campo) {
                    case "text":
                        
                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>'+
                                            '<div class="input-group mb-3">';

                        if(campo.variable == 'numero_documento'){
                            if(campo.editable && campo.requerido){
                                html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '">';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required readonly>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" readonly>';
                            }
                        }else{
                            if(campo.editable && campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '">';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required readonly>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" readonly>';
                            }
                        }
                        
                        if(long == count){
                            html_components += '</div></div></div></div>';
                        }else{
                            if(count % 2 === 0){
                                html_components += '</div></div></div><div class="form-row">';
                            }else{
                                html_components += '</div></div>';
                            }
                        }
                        count ++;
                        
                        break;
                    case "date":

                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.date_field_name + '">' + campo.nombre + '</label>'+
                                            '<div class="datepicker date input-group">';

                        if(campo.editable && campo.requerido){
                            html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + campo.configuracion.date_field_value + '" required>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + campo.configuracion.date_field_value + '">';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + campo.configuracion.date_field_value + '" required readonly>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + campo.configuracion.date_field_value + '" readonly>';
                        }

                        html_components += '<div class="input-group-append">';
                        html_components += '<span class="input-group-text"><i class="fa fa-calendar"></i></span>';
                        html_components += '</div>';

                        if(long == count){
                            html_components += '</div></div></div></div></div>';
                        }else{
                            if(count % 2 === 0){
                                html_components += '</div></div></div><div class="form-row">';
                            }else{
                                html_components += '</div></div>';
                            }
                        }
                        count ++;
                        
                        break;
                    case "number":

                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.number_field_name + '">' + campo.nombre + '</label>'+
                                            '<div class="input-group mb-3">';

                        if(campo.editable && campo.requerido){
                            html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + campo.configuracion.number_field_value + '" required>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + campo.configuracion.number_field_value + '">';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + campo.configuracion.number_field_value + '" required readonly>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + campo.configuracion.number_field_value + '" readonly>';
                        }

                        if(long == count){
                            html_components +='</div></div></div></div>';
                        }else{
                            if(count % 2 === 0){
                                html_components +='</div></div></div><div class="form-row">';
                            }else{
                                html_components +='</div></div>';
                            }
                        }
                        count ++;
                        
                        break;
                    case "email":

                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="nombre">' + campo.nombre + '</label>'+
                                            '<div class="input-group mb-3">';

                        if(campo.editable && campo.requerido){
                            html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + campo.configuracion.email_field_value + '" required>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + campo.configuracion.email_field_value + '">';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + campo.configuracion.email_field_value + '" required readonly>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + campo.configuracion.email_field_value + '" readonly>';
                        }

                        if(long == count){
                            html_components +='</div></div></div></div>';
                        }else{
                            if(count % 2 === 0){
                                html_components +='</div></div></div><div class="form-row">';
                            }else{
                                html_components +='</div></div>';
                            }
                        }
                        count ++;
                        
                        break;
                    case "file":

                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.file_field_name + '">' + campo.nombre + '</label>';

                        if(campo.editable && campo.requerido){
                            html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf" required>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf">';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf" required readonly>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf" readonly>';
                        }

                        if(long == count){
                            html_components += '</div></div></div></div>';
                        }else{
                            if(count % 2 === 0){
                                html_components += '</div></div><div class="form-row">';
                            }else{
                                html_components += '</div>';
                            }
                        }
                        count ++;
                        
                        break;
                    case "select":

                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.select_field_name + '">' + campo.nombre + '</label>';

                        if(campo.editable && campo.requerido){
                            html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" required>';
                            for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                                if(typeof campo.configuracion.select_field_default_value !== 'undefined' && campo.configuracion.select_field_default_value !== null){
                                    if(campo.configuracion.select_field_default_value == catalogo.id){
                                        html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }else{
                                        html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }
                                }else{
                                    html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }
                            }
                            html_components += '</select>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true">';
                            for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                                if(typeof campo.configuracion.select_field_default_value !== 'undefined' && campo.configuracion.select_field_default_value !== null){
                                    if(campo.configuracion.select_field_default_value == catalogo.id){
                                        html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }else{
                                        html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }
                                }else{
                                    html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }
                            }
                            html_components += '</select>';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" required readonly>';
                            for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                                if(typeof campo.configuracion.select_field_default_value !== 'undefined' && campo.configuracion.select_field_default_value !== null){
                                    if(campo.configuracion.select_field_default_value == catalogo.id){
                                        html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }else{
                                        html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }
                                }else{
                                    html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }
                            }
                            html_components += '</select>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" readonly>';
                            for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                                if(typeof campo.configuracion.select_field_default_value !== 'undefined' && campo.configuracion.select_field_default_value !== null){
                                    if(campo.configuracion.select_field_default_value == catalogo.id){
                                        html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }else{
                                        html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }
                                }else{
                                    html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }
                            }
                            html_components += '</select>';
                        }

                        if(long == count){
                            html_components +='</div></div></div></div>';
                        }else{
                            if(count % 2 === 0){
                                html_components +='</div></div><div class="form-row">';
                            }else{
                                html_components +='</div>';
                            }
                        }
                        count ++;
                        
                        break;
                }
                //count ++;
            }
            return html_components;
        }

        function renderFilterForm2(){
            let html_components = "";
            let listaCampos = 'listaCampos';
            listaCampos = listaCampos.replace(/&quot;/g, '"');
            listaCampos = JSON.parse(listaCampos);

            camposPorSeccion = Object.groupBy(listaCampos, (campo) => campo.seccion_campo);
            console.log(camposPorSeccion);

            inicializarObjeto(camposPorSeccion);

            html_components += '<div class="accordion" id="accordion">';
            
            for (let seccion in camposPorSeccion) {
                let count = 1;
                let long = camposPorSeccion[seccion].filter(campo => campo.visible === true).length;
                
                if(long > 0){
                    html_components += '<div class="card">'+
                    '<div class="card-header" id="headingOne">'+
                    '<h5 class="mb-0">'+
                    '<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#' + seccion + '" aria-expanded="true" aria-controls="' + seccion + '">' + seccion + '</button>'+
                    '</h5>'+
                    '</div>'+
                    '<div id="' + seccion + '" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">'+
                    '<div class="card-body">';
                    if(seccion == 'BENEFICIARIOS'){
                        html_components += '<div id="beneficiario_' + countBeneficiario + '" class="card">'+
                        '<div class="card-header">'+
                        'Beneficiario'+
                        '<a style="float: right; padding-left:5px; padding-right:5px;" class="icon-margin" title="Agregar" href="javascript:void(0);" onclick="event.preventDefault(); agregarBeneficiario(this)"><i class="fa fa-plus fa-2x"></i></a>'+
                        '</div>'+
                        '<div class="card-body">';
                    }
                    html_components += '<div class="form-row">';

                    html_components += contruirCampos(count,long,seccion);

                    html_components += '</div>';
                    if(seccion == 'BENEFICIARIOS'){
                        html_components += '</div>'+
                        '</div>';
                    }
                }
            }
            html_components += '</div>'
            $("#creacionTramite").append(html_components);
        }

        function contruirCampos(count,long,seccion){
            let html_components = '';
            for (let campo of camposPorSeccion[seccion]) {
                switch (campo.tipo_campo) {
                    case "text":
                        
                        if(campo.visible){
                            html_components += '<div class="form-group col-md-6 col-sm-12">';
                            html_components += '<label for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>'+
                                                '<div class="input-group mb-3">';

                            if(campo.variable == 'numero_documento'){
                                if(campo.editable && campo.requerido){
                                    html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required>';
                                }else if(campo.editable && !campo.requerido){
                                    html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '">';
                                }else if(!campo.editable && campo.requerido){
                                    html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required readonly>';
                                }else if(!campo.editable && !campo.requerido){
                                    html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" readonly>';
                                }
                            }else{
                                if(campo.editable && campo.requerido){
                                    html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required>';
                                }else if(campo.editable && !campo.requerido){
                                    html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '">';
                                }else if(!campo.editable && campo.requerido){
                                    html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required readonly>';
                                }else if(!campo.editable && !campo.requerido){
                                    html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" readonly>';
                                }
                            }
                            
                            if(long == count){
                                html_components += '</div></div></div></div>';
                            }else{
                                if(count % 2 === 0){
                                    html_components += '</div></div></div><div class="form-row">';
                                }else{
                                    html_components += '</div></div>';
                                }
                            }
                            count ++;
                        }

                        break;
                    case "date":

                        if(campo.visible){
                            html_components += '<div class="form-group col-md-6 col-sm-12">';
                            html_components += '<label for="' + campo.configuracion.date_field_name + '">' + campo.nombre + '</label>'+
                                                '<div class="datepicker date input-group">';

                            if(campo.editable && campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + campo.configuracion.date_field_value + '" required>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + campo.configuracion.date_field_value + '">';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + campo.configuracion.date_field_value + '" required readonly>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + campo.configuracion.date_field_value + '" readonly>';
                            }

                            html_components += '<div class="input-group-append">';
                            html_components += '<span class="input-group-text"><i class="fa fa-calendar"></i></span>';
                            html_components += '</div>';

                            if(long == count){
                                html_components += '</div></div></div></div></div>';
                            }else{
                                if(count % 2 === 0){
                                    html_components += '</div></div></div><div class="form-row">';
                                }else{
                                    html_components += '</div></div>';
                                }
                            }
                            count ++;
                        }
                        
                        break;
                    case "number":

                        if(campo.visible){
                            html_components += '<div class="form-group col-md-6 col-sm-12">';
                            html_components += '<label for="' + campo.configuracion.number_field_name + '">' + campo.nombre + '</label>'+
                                                '<div class="input-group mb-3">';

                            if(campo.editable && campo.requerido){
                                html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + campo.configuracion.number_field_value + '" required>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + campo.configuracion.number_field_value + '">';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + campo.configuracion.number_field_value + '" required readonly>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + campo.configuracion.number_field_value + '" readonly>';
                            }

                            if(long == count){
                                html_components +='</div></div></div></div>';
                            }else{
                                if(count % 2 === 0){
                                    html_components +='</div></div></div><div class="form-row">';
                                }else{
                                    html_components +='</div></div>';
                                }
                            }
                            count ++;
                        }
                        
                        break;
                    case "email":

                        if(campo.visible){
                            html_components += '<div class="form-group col-md-6 col-sm-12">';
                            html_components += '<label for="nombre">' + campo.nombre + '</label>'+
                                                '<div class="input-group mb-3">';

                            if(campo.editable && campo.requerido){
                                html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + campo.configuracion.email_field_value + '" required>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + campo.configuracion.email_field_value + '">';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + campo.configuracion.email_field_value + '" required readonly>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + campo.configuracion.email_field_value + '" readonly>';
                            }

                            if(long == count){
                                html_components +='</div></div></div></div>';
                            }else{
                                if(count % 2 === 0){
                                    html_components +='</div></div></div><div class="form-row">';
                                }else{
                                    html_components +='</div></div>';
                                }
                            }
                            count ++;
                        }
                        
                        break;
                    case "file":

                        if(campo.visible){
                            html_components += '<div class="form-group col-md-6 col-sm-12">';
                            html_components += '<label for="' + campo.configuracion.file_field_name + '">' + campo.nombre + '</label>';

                            if(campo.editable && campo.requerido){
                                html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf" required>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf">';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf" required readonly>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf" readonly>';
                            }

                            if(long == count){
                                html_components += '</div></div></div></div>';
                            }else{
                                if(count % 2 === 0){
                                    html_components += '</div></div><div class="form-row">';
                                }else{
                                    html_components += '</div>';
                                }
                            }
                            count ++;
                        }
                        
                        break;
                    case "select":

                        if(campo.visible){
                            html_components += '<div class="form-group col-md-6 col-sm-12">';
                            html_components += '<label for="' + campo.configuracion.select_field_name + '">' + campo.nombre + '</label>';

                            if(campo.editable && campo.requerido){
                                html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" required>';
                                for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                                    if(typeof campo.configuracion.select_field_default_value !== 'undefined' && campo.configuracion.select_field_default_value !== null){
                                        if(campo.configuracion.select_field_default_value == catalogo.id){
                                            html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                        }else{
                                            html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                        }
                                    }else{
                                        html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }
                                }
                                html_components += '</select>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true">';
                                for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                                    if(typeof campo.configuracion.select_field_default_value !== 'undefined' && campo.configuracion.select_field_default_value !== null){
                                        if(campo.configuracion.select_field_default_value == catalogo.id){
                                            html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                        }else{
                                            html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                        }
                                    }else{
                                        html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }
                                }
                                html_components += '</select>';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" required readonly>';
                                for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                                    if(typeof campo.configuracion.select_field_default_value !== 'undefined' && campo.configuracion.select_field_default_value !== null){
                                        if(campo.configuracion.select_field_default_value == catalogo.id){
                                            html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                        }else{
                                            html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                        }
                                    }else{
                                        html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }
                                }
                                html_components += '</select>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" readonly>';
                                for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                                    if(typeof campo.configuracion.select_field_default_value !== 'undefined' && campo.configuracion.select_field_default_value !== null){
                                        if(campo.configuracion.select_field_default_value == catalogo.id){
                                            html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                        }else{
                                            html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                        }
                                    }else{
                                        html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                    }
                                }
                                html_components += '</select>';
                            }

                            if(long == count){
                                html_components +='</div></div></div></div>';
                            }else{
                                if(count % 2 === 0){
                                    html_components +='</div></div><div class="form-row">';
                                }else{
                                    html_components +='</div>';
                                }
                            }
                            count ++;
                        }
                        
                        break;
                }
                //count ++;
            }
            return html_components;
        }
        
     </script>
@endsection