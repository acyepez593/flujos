@extends('backend.layouts.master')

@section('title')
    {{ __('Reportes - Panel de Reporte Registros Bitácora') }}
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
                                                <label for="fecha_recepcion_desde_search">Buscar por Fecha Recepción Desde</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" placeholder="" class="form-control" id="fecha_recepcion_desde_search" name="fecha_recepcion_desde_search" value="{{ old('fecha_recepcion_desde_search') }}">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                                @error('fecha_recepcion_desde_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="fecha_recepcion_hasta_search">Buscar por Fecha Recepción Hasta</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" placeholder="" class="form-control" id="fecha_recepcion_hasta_search" name="fecha_recepcion_hasta_search" value="{{ old('fecha_recepcion_hasta_search') }}">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                                @error('fecha_recepcion_hasta_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_documento_id_search">Buscar por Tipo Documento:</label>
                                                <select id="tipo_documento_id_search" name="tipo_documento_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Tipo Documento</option>
                                                    @foreach ($tiposDocumento as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('tipo_documento_id_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_ingreso_id_search">Buscar por Tipo Ingreso:</label>
                                                <select id="tipo_ingreso_id_search" name="tipo_ingreso_id" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Tipo Ingreso</option>
                                                    @foreach ($tiposIngreso as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('tipo_ingreso_id_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_atencion_id_search">Buscar por Tipo de Atención:</label>
                                                <select id="tipo_atencion_id_search" name="tipo_atencion_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione una Tipo de Atención</option>
                                                    @foreach ($tiposAtencion as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('tipo_atencion_id_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="numero_casos_search">Buscar por # Casos</label>
                                                <input type="text" class="form-control int-number" id="numero_casos_search" name="numero_casos_search" placeholder="# Expedientes" value="{{ old('numero_casos_search') }}">
                                                @error('numero_casos_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="receptor_documental_id_search">Buscar por Receptor Documental:</label>
                                                <select id="receptor_documental_id_search" name="receptor_documental_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Receptor Documental</option>
                                                    @foreach ($receptoresDocumental as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('receptor_documental_id_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="nombre_prestador_salud_search">Buscar por Prestador</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="nombre_prestador_salud_search" name="nombre_prestador_salud_search" placeholder="" value="{{ old('nombre_prestador_salud_search') }}">
                                                    @error('nombre_prestador_salud_search')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="monto_planilla_search">Buscar por Monto Planilla</label>
                                                <input type="text" class="form-control decimal-number" id="monto_planilla_search" name="monto_planilla_search" placeholder="" step="0.25" value="{{ old('monto_planilla_search') }}">
                                                @error('monto_planilla_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="fecha_servicio_desde_search">Buscar por Fecha de Servicio Desde</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" placeholder="" class="form-control" id="fecha_servicio_desde_search" name="fecha_servicio_desde_search" value="{{ old('fecha_servicio_desde_search') }}">
                                                    <div class="input-group-append" style="display: none;">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                                @error('fecha_servicio_desde_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="fecha_servicio_hasta_search">Buscar por Fecha de Servicio Hasta</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" placeholder="" class="form-control" id="fecha_servicio_hasta_search" name="fecha_servicio_hasta_search" value="{{ old('fecha_servicio_hasta_search') }}">
                                                    <div class="input-group-append" style="display: none;">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                                @error('fecha_servicio_hasta_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="descripcion_search">Buscar por Observaciones</label>
                                                <input type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase();" id="descripcion_search" name="descripcion_search" placeholder="" value="{{ old('descripcion_search') }}">
                                                @error('descripcion_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_tramite_id_search">Buscar por Tipo de Trámite:</label>
                                                <select id="tipo_tramite_id_search" name="tipo_tramite_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Tipo de Trámite</option>
                                                    @foreach ($tiposTramite as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('tipo_tramite_id_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12" id="periodo_extemporaneo" style="display: none;">
                                                <label for="periodo_search">Periodo</label>
                                                <input type="text" class="form-control int-number" id="periodo_search" name="periodo_search" placeholder="" value="{{ old('periodo_search') }}">
                                                @error('periodo_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="numero_quipux_search">Buscar por Número de Quipux</label>
                                                <input type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase();" id="numero_quipux_search" name="numero_quipux_search" placeholder="Número de Quipux" value="{{ old('numero_quipux_search') }}">
                                                @error('numero_quipux_search')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="responsable_id_search">Buscar por Responsable:</label>
                                                <select id="responsable_id_search" name="responsable_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Responsable</option>
                                                    @foreach ($responsables as $key => $value)
                                                        <option value="{{ $key }}" {{ Auth::user()->id == $key ? 'selected' : ''}}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if (auth()->user()->can('reporteBitacora.download'))
                                        <div class="col-sm-2">
                                            <button id="generarReporteBitacora" type="button" class="btn btn-success mt-4 pr-4 pl-4">Generar Reporte</button>
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
        let dataTableData = {
            totalRegistros : 0,
            totalNumCasos : 0,
            totalMontoPlanilla : 0
        };
        let tableRef = "";
        let tableHeaderRef = "";
        let registros = [];
        let tipos = [];
        let tipos_atencion = [];
        let provincias = [];
        let instituciones = [];
        let tipos_firma = [];
        let responsables = [];
        let selected_table_items = [];

        $(document).ready(function() {

            $( "#generarReporteBitacora" ).on( "click", function() {
                if(document.getElementById('reporte').reportValidity()){
                    $("#overlay").fadeIn(300);
                    $.ajax({
                        url: "{{url('/generarReporteBitacoraByFilters')}}",
                        method: "POST",
                        data: {
                            fecha_recepcion_desde_search: $('#fecha_recepcion_desde_search').val(),
                            fecha_recepcion_hasta_search: $('#fecha_recepcion_hasta_search').val(),
                            tipo_documento_id_search: JSON.stringify($('#tipo_documento_id_search').val()),
                            tipo_ingreso_id_search: JSON.stringify($('#tipo_ingreso_id_search').val()),
                            tipo_atencion_id_search: JSON.stringify($('#tipo_atencion_id_search').val()),
                            numero_casos_search: $('#numero_casos_search').val(),
                            receptor_documental_id_search: JSON.stringify($('#receptor_documental_id_search').val()),
                            nombre_prestador_salud_search: $('#nombre_prestador_salud_search').val(),
                            monto_planilla_search: $('#monto_planilla_search').val(),
                            descripcion_search: $('#descripcion_search').val(),
                            fecha_servicio_desde_search: $('#fecha_servicio_desde_search').val(),
                            fecha_servicio_hasta_search: $('#fecha_servicio_hasta_search').val(),
                            tipo_tramite_id_search: JSON.stringify($('#tipo_tramite_id_search').val()),
                            periodo_search: $('#periodo_search').val(),
                            numero_quipux_search: $('#numero_quipux_search').val(),
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
                            a.download = 'reporteBitacora.xlsx';
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