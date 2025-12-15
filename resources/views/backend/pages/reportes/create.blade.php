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
                                                <label for="tipo_reporte_search">Seleccione un Reporte:</label>
                                                <select id="tipo_reporte_search" name="tipo_reporte_search" class="form-control selectpicker" data-live-search="true" required>
                                                    <option value="">Seleccione un Reporte</option>    
                                                    @foreach ($tiposReporte as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
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
        let campos = [];

        $(document).ready(function() {

            $( "#tipo_reporte_search" ).on( "change", function() {
                $.ajax({
                    url: "{{url('/getCamposByTipoReporte')}}",
                    method: "POST",
                    data: {
                        tipo_reporte_search: $('#tipo_reporte_search').val(),
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        $("#overlay").fadeOut(300);

                        campos = response.campos;

                        $('#campo_search').selectpicker('destroy');
                        $("#campo_search").html('');
                        $.each(campos, function (key, value) {
                            $("#campo_search").append('<option value="' + value.nombre_seccion + '-' + value.campo + '">' + value.nombre_seccion + '-' + value.nombre_campo + '</option>');
                        });
                        $('#campo_search').selectpicker();
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
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
        
     </script>
@endsection