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
                                                <label for="secuencia_proceso_id_search">Seleccione una Secuencia:</label>
                                                <select id="secuencia_proceso_id_search" name="secuencia_proceso_id_search" class="form-control selectpicker" data-live-search="true">
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="funcionario_actual_id_search">Seleccione un Funcionario:</label>
                                                <select id="funcionario_actual_id_search" name="funcionario_actual_id_search" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">Seleccione un Proceso</option>    
                                                    @foreach ($funcionarios as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="estatus_id_search">Seleccione un Estatus:</label>
                                                <select id="estatus_id_search" name="estatus_id_search" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">Seleccione un Estatus</option>
                                                    <option value="INGRESADO">INGRESADO</option>
                                                    <option value="EN PROCESO DAP">EN PROCESO DAP</option>
                                                    <option value="EN ANALISIS DE PROCEDENCIA">EN ANALISIS DE PROCEDENCIA</option>
                                                    <option value="EN PROCESO FINANCIERO">EN PROCESO FINANCIERO</option>
                                                    <option value="PAGADO">PAGADO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="fecha_creacion_tramite_desde_search">Fecha de Creación Desde</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" id="fecha_creacion_tramite_desde_search" class="form-control datepicker" name="fecha_creacion_tramite_desde_search">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="fecha_creacion_tramite_hasta_search">Fecha de Creación Hasta</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" id="fecha_creacion_tramite_hasta_search" class="form-control datepicker" name="fecha_creacion_tramite_hasta_search">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_reporte_search">Seleccione un Reporte:</label>
                                                <select id="tipo_reporte_search" name="tipo_reporte_search" class="form-control selectpicker" data-live-search="true" required>
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="campo_search">Seleccione los campos para el filtro:</label>
                                                <select id="campo_search" name="campo_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    
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
        let secuenciasProcesos = [];
        let tiposReporte = [];
        let catalogos = [];
        let listaCampos = [];
        let campos = [];
        let camposPorSeccion = [];
        let count = 0;
        let objFiltros = [];

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

                        secuenciasProcesos = response.secuenciasProcesos;
                        tiposReporte = response.tiposReporte;
                        listaCampos = response.listaCampos;
                        catalogos = response.catalogos;

                        $("#filtros").html('');
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
                        
                        $('#secuencia_proceso_id_search').selectpicker('destroy');
                        $("#secuencia_proceso_id_search").html('');
                        $("#secuencia_proceso_id_search").append('<option value="">Seleccione una Secuencia</option>');
                        $.each(secuenciasProcesos, function (key, value) {
                            $("#secuencia_proceso_id_search").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                        $('#secuencia_proceso_id_search').selectpicker();
                        $('.selectpicker').selectpicker('refresh');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.status);
                        console.log(error);
                        $("#filtros").html('');
                        $('#secuencia_proceso_id_search').selectpicker('destroy');
                        $("#secuencia_proceso_id_search").html('');
                        $('#secuencia_proceso_id_search').selectpicker();

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

                        $("#filtros").html('');
                        $('#campo_search').selectpicker('destroy');
                        $("#campo_search").html('');
                        $("#campo_search").append('<option value="">Seleccione los campos para el filtro</option>');
                        $.each(campos, function (key, value) {
                            $("#campo_search").append('<option value="' + value.nombre_seccion + '-' + value.campo + '">' + value.nombre_seccion + '-' + value.nombre_campo + '</option>');
                        });
                        $('#campo_search').selectpicker();
                        $('.selectpicker').selectpicker('refresh');

                        inicializarObjetoFiltros(campos);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.status);
                        console.log(error);
                        $("#filtros").html('');
                        $('#campo_search').selectpicker('destroy');
                        $("#campo_search").html('');
                        $('#campo_search').selectpicker();
                    }
                });
            });

            $( "#campo_search" ).on( "change", function() {
                renderFilterForm($(this).val());
            });

            $( "#generarReporte" ).on( "click", function() {
                if(document.getElementById('reporte').reportValidity()){
                    $("#overlay").fadeIn(300);
                    /*$.ajax({
                    url: "{{url('/generarReporteByTipoReporte')}}",
                    method: "POST",
                    data: {
                        proceso_id_search: $('#proceso_id_search').val(),
                        secuencia_proceso_id_search: JSON.stringify($('#secuencia_proceso_id_search').val()),
                        funcionario_actual_id_search: JSON.stringify($('#funcionario_actual_id_search').val()),
                        estatus_id_search: JSON.stringify($('#estatus_id_search').val()),
                        fecha_creacion_tramite_desde_search: $('#fecha_creacion_tramite_desde_search').val(),
                        fecha_creacion_tramite_hasta_search: $('#fecha_creacion_tramite_hasta_search').val(),
                        tipo_reporte_search: $('#tipo_reporte_search').val(),
                        filtros_search: JSON.stringify(objFiltros),
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        $("#overlay").fadeOut(300);
                        console.log('response');
                        console.log(response);
                        
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.status);
                        console.log(error);
                    }
                });*/

                    $.ajax({
                        url: "{{url('/generarReporteByTipoReporte')}}",
                        method: "POST",
                        data: {
                            proceso_id_search: $('#proceso_id_search').val(),
                            secuencia_proceso_id_search: JSON.stringify($('#secuencia_proceso_id_search').val()),
                            funcionario_actual_id_search: JSON.stringify($('#funcionario_actual_id_search').val()),
                            estatus_id_search: JSON.stringify($('#estatus_id_search').val()),
                            fecha_creacion_tramite_desde_search: $('#fecha_creacion_tramite_desde_search').val(),
                            fecha_creacion_tramite_hasta_search: $('#fecha_creacion_tramite_hasta_search').val(),
                            tipo_reporte_search: $('#tipo_reporte_search').val(),
                            filtros_search: JSON.stringify(objFiltros),
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

        });
        
        function inicializarObjetoFiltros(campos){
            objFiltros = campos;

            for (let campo of objFiltros) {
                campo.valor_filtro = '';
            }
        }

        function setValuesObjFilters(seccion,campo,valor){
            let objFiltrado = objFiltros.find(obj => obj.nombre_seccion === seccion && obj.campo === campo);
            objFiltrado.valor_filtro = valor.value;
        }

        function renderFilterForm(campos_seleccionados){
            let html_components = "";
            let long = campos_seleccionados.length;
            

            camposPorSeccion = Object.groupBy(listaCampos, (campo) => campo.seccion_campo);
            console.log(camposPorSeccion);
            count = 1;
            
            for (let index in campos_seleccionados) {
            
                let seccion = '';
                let campo_a_mostrar = '';

                let obj = campos_seleccionados[index].split('-');
                seccion = obj[0];
                campo_a_mostrar = obj[1];

                if(index == 0){
                    html_components += '<div class="form-row">';
                }
                
                html_components += contruirFiltros(long,seccion,campo_a_mostrar);

            }

            $("#filtros").append(html_components);
            $('.selectpicker').selectpicker('refresh');
            $('.datepicker').datepicker({
                language: 'es',
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
                endDate: 0
            });
        }

        function contruirFiltros(long,seccion,campo_a_mostrar){
            $("#filtros").html('');
            let html_components = '';
            for (let campo of camposPorSeccion[seccion]) {
                let seccion_campo = {nombre_seccion : seccion};
                if(campo.variable == campo_a_mostrar){
                    switch (campo.tipo_campo) {
                        case "text":
                            
                            if(campo.variable == campo_a_mostrar){
                                html_components += '<div class="form-group col-md-6 col-sm-12">';

                                html_components += '<label for="' + campo.configuracion.text_field_name + '">' + campo.nombre + ' ('+ seccion +')</label>'+
                                                    '<div class="input-group mb-3">';

                                html_components += '<input type="text" onchange="setValuesObjFilters(`' + seccion + '`,`' + campo.variable + '`,this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '">';
                            }
                                                
                            if(long == count){
                                html_components += '</div></div></div>';
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

                            if(campo.variable == campo_a_mostrar){
                                html_components += '<div class="form-group col-md-6 col-sm-12">';
                                html_components += '<label for="' + campo.configuracion.date_field_name + '">' + campo.nombre + ' ('+ seccion +')</label>'+
                                                    '<div class="datepicker date input-group">';

                                html_components += '<input type="text" onchange="setValuesObjFilters(`' + seccion + '`,`' + campo.variable + '`,this)" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + campo.configuracion.date_field_value + '">';

                                html_components += '<div class="input-group-append">';
                                html_components += '<span class="input-group-text"><i class="fa fa-calendar"></i></span>';
                                html_components += '</div>';
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
                        case "number":

                            if(campo.variable == campo_a_mostrar){
                                html_components += '<div class="form-group col-md-6 col-sm-12">';
                                html_components += '<label for="' + campo.configuracion.number_field_name + '">' + campo.nombre + ' ('+ seccion +')</label>'+
                                                    '<div class="input-group mb-3">';

                                html_components += '<input type="number" onchange="setValuesObjFilters(`' + seccion + '`,`' + campo.variable + '`,this)" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + campo.configuracion.number_field_value + '">';
                            }

                            if(long == count){
                                html_components +='</div></div></div>';
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

                            if(campo.variable == campo_a_mostrar){
                                html_components += '<div class="form-group col-md-6 col-sm-12">';
                                html_components += '<label for="nombre">' + campo.nombre + ' ('+ seccion +')</label>'+
                                                    '<div class="input-group mb-3">';

                                html_components += '<input type="email" onchange="setValuesObjFilters(`' + seccion + '`,`' + campo.variable + '`,this)" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + campo.configuracion.email_field_value + '">';

                            }

                            if(long == count){
                                html_components +='</div></div></div>';
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

                            if(campo.variable == campo_a_mostrar){
                                html_components += '<div class="form-group col-md-6 col-sm-12">';
                                html_components += '<label for="' + campo.configuracion.file_field_name + '">' + campo.nombre + ' ('+ seccion +')</label>';

                                html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf">';
                            }

                            if(long == count){
                                html_components += '</div></div></div>';
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

                            if(campo.variable == campo_a_mostrar){
                                html_components += '<div class="form-group col-md-6 col-sm-12">';
                                html_components += '<label for="' + campo.configuracion.select_field_name + '">' + campo.nombre + ' ('+ seccion +')</label>';

                                if(catalogos[campo.configuracion.select_field_tipo_catalogo] !== undefined && catalogos[campo.configuracion.select_field_tipo_catalogo] !== 'undefined' && catalogos[campo.configuracion.select_field_tipo_catalogo] !== null){
                                    html_components += '<select onchange="setValuesObjFilters(`' + seccion + '`,`' + campo.variable + '`,this)" name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true">';
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
                            }

                            if(long == count){
                                html_components +='</div></div></div>';
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
                }
                //count ++;
            }
            return html_components;
        }
        
     </script>
@endsection