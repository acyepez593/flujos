@extends('backend.layouts.master')

@section('title')
    {{ __('Procesos - Panel de Proceso') }}
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
                <h4 class="page-title pull-left">{{ __('Procesos') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('Todos los Procesos') }}</span></li>
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
                                    <form>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="nombre_search">Buscar por Nombre</label>
                                                <input type="text" class="form-control" id="nombre_search" name="nombre_search">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="descripcion_search">Buscar por Descripción</label>
                                                <input type="text" class="form-control" id="descripcion_search" name="descripcion_search">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_id_search">Buscar por Tipo:</label>
                                                <select id="tipo_id_search" name="tipo_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Tipo</option>
                                                    @foreach ($tipos as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="creador_id_search">Buscar por Creador:</label>
                                                <select id="creador_id_search" name="creador_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Responsable</option>
                                                    @foreach ($responsables as $key => $value)
                                                        <option value="{{ $value->id }}" {{ Auth::user()->id == $value->id ? 'selected' : ''}}>{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <button type="button" id="buscarProcesos" class="btn btn-primary mt-4 pr-4 pl-4">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Procesos
                                </button>
                            </h5>
                            </div>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <h4 class="header-title float-left">{{ __('Oficios') }}</h4>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('oficio.create'))
                                            <a class="btn btn-primary text-white" href="{{ route('admin.oficios.create') }}">
                                                {{ __('Crear Nuevo') }}
                                            </a>
                                        @endif
                                    </p>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('oficio.asignarNumeroCajaAuditoria'))
                                            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modalAsignarNumeroCajaAuditoria">Asignar Número Caja Auditoría</button>
                                        @endif
                                    </p>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('oficio.asignarFechaEnvioAuditoria'))
                                            <button class="btn btn-secundary" type="button" data-toggle="modal" data-target="#modalAsignarFechaEnvioAuditoria">Asignar Fecha Envío Auditoría</button>
                                        @endif
                                    </p>
                                    <p class="float-right mb-2" style="padding: 5px;" data-toggle="tooltip" title="Asigna Número Caja Auditoría y Fecha Envio Auditoría por el Número de Caja de tipo Cerrada">
                                        @if (auth()->user()->can('oficio.asignarPorNumeroCaja'))
                                            <button class="btn btn-warning" type="button" data-toggle="modal" data-target="#modalAsignarPorNumeroCaja">Asignar por Número de Caja</button>
                                        @endif
                                    </p>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('oficio.cerrarCaja'))
                                            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#modalCerrarCaja" @if(count($cajasAbiertas) <= 0) style="cursor: not-allowed;" disabled @endif>Cerrar Caja</button>
                                        @endif
                                    </p>
                                    <div class="clearfix"></div>
                                    <div class="data-tables">
                                        <div class="col-6 mt-6">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td><b>Total Registros</b></td>
                                                        <td id="totalRegistros"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Total Número de Casos</b></td>
                                                        <td id="totalNumCasos"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Total Monto Planilla</b></td>
                                                        <td id="totalMontoPlanilla"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="data-tables">
                                        
                                        <table id="dataTable" class="text-center">
                                            <thead class="bg-light text-capitalize">
                                                
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>

                                        <!--<table id="dataTable1" class="text-center">
                                            <thead class="bg-light text-capitalize">
                                                <th>#</th>
                                                <th>Fecha Registro</th>
                                                <th>Tipo</th>
                                                <th>Ruc</th>
                                                <th># Establecimiento</th>
                                                <th>Razón Social</th>
                                                <th>Fecha Recepción</th>
                                                <th>Tipo de Atención</th>
                                                <th>Provincia</th>
                                                <th>Mes y Año del Servicio</th>
                                                <th>Número de Expedientes</th>
                                                <th>Monto Planilla</th>
                                                <th>Número de Caja Anterior</th>
                                                <th>Escoger</th>
                                                <th>Número de Caja</th>
                                                <th>Estado Caja</th>
                                                <th>Número de Caja Auditoría</th>
                                                <th>Fecha Envio Auditoría</th>
                                                <th>Tipo de Institución</th>
                                                <th>Documento Externo</th>
                                                <th>Tipo de Firma del Documento</th>
                                                <th>Observaciones</th>
                                                <th># de Quipux</th>
                                                <th>Estado Trámite</th>
                                                <th>Fecha Devolución Auditoría</th>
                                                <th>Observaciones Devolución Auditoría</th>
                                                <th>Fecha Devolución Prestador</th>
                                                <th>Observaciones Devolución Prestador</th>
                                                <th>Responsable</th>
                                                <th>Archivos</th>
                                                <th>Acción</th>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->
        <!-- Modal -->
        <div class="modal fade" id="modalAsignarNumeroCajaAuditoria" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Asignar Número de Caja Auditoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12 col-sm-12">
                            <label for="valor_numero_caja_auditoria">Número de Caja Auditoría</label>
                            <input type="text" class="form-control" id="valor_numero_caja_auditoria" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="asignarNumeroCajaAuditoria" class="btn btn-primary">Actualizar</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalAsignarFechaEnvioAuditoria" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Asignar Fecha Envío Auditoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12 col-sm-12">
                            <label for="valor_fecha_recepcion">Fecha Envío Auditoría</label>
                            <div class="datepicker date input-group">
                                <input type="text" placeholder="Fecha Envío Auditoría" class="form-control" id="valor_fecha_envio_auditoria" name="valor_fecha_envio_auditoria" value="">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="asignarFechaEnvioAuditoria" class="btn btn-primary">Actualizar</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalCerrarCaja" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Cerrar Caja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="valor_numero_caja">Seleccione una Caja:</label>
                            <select id="valor_numero_caja" name="valor_numero_caja" class="form-control selectpicker" data-live-search="true" required>
                                
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="cerrarNumeroCaja" class="btn btn-primary">Cerrar Caja</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalAsignarPorNumeroCaja" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Asignar Número Caja Auditoría y Fecha Envio Auditoría por Número de Caja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="valor_numero_caja_por_numero_caja">Seleccione una Caja:</label>
                            <select id="valor_numero_caja_por_numero_caja" name="valor_numero_caja_por_numero_caja" class="form-control selectpicker" data-live-search="true" multiple required>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="valor_numero_caja_auditoria_por_numero_caja">Número de Caja Auditoría</label>
                            <input type="text" class="form-control" id="valor_numero_caja_auditoria_por_numero_caja">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="valor_fecha_recepcion_por_numero_caja">Fecha Envío Auditoría</label>
                            <div class="datepicker date input-group">
                                <input type="text" placeholder="Fecha Envío Auditoría" class="form-control" id="valor_fecha_envio_auditoria_por_numero_caja" name="valor_fecha_envio_auditoria">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="asignarPorNumeroCaja" class="btn btn-primary">Actualizar</button>
                </div>
                </div>
            </div>
        </div>
        
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
        let oficios = [];
        let tipos = [];
        let tipos_atencion = [];
        let tipos_estado_caja = [];
        let provincias = [];
        let instituciones = [];
        let tipos_firma = [];
        let responsables = [];
        let selected_table_items = [];
        let roles_user_auth_ids = {{Auth::user()->roles->pluck('id')}};
        let meses = [{"id":"01","nombre":"Enero"},{"id":"02","nombre":"Febrero"},{"id":"03","nombre":"Marzo"},{"id":"04","nombre":"Abril"},{"id":"05","nombre":"Mayo"},{"id":"06","nombre":"Junio"},{"id":"07","nombre":"Julio"},{"id":"08","nombre":"Agosto"},{"id":"09","nombre":"Septiembre"},{"id":"10","nombre":"Octubre"},{"id":"11","nombre":"Noviembre"},{"id":"12","nombre":"Diciembre"}];
        let usuario_actual = '{{ Auth::user()->id }}';
        let oficios_files = "";

        $(document).ready(function() {

            /*$('#prestador1_search, #prestador2_search, #prestador_salud_search, #responsable_planillaje_search').on('keyup', function () {
                this.value = this.value.toUpperCase();
            });*/

            $( "#buscarOficios" ).on( "click", function() {
                $("#overlay").fadeIn(300);
                $('#dataTable').empty();

                var tabla = $('#dataTable');
                var thead = $('<thead></thead>').appendTo(tabla);
                var tbody = $('<tbody><tbody/>').appendTo(tabla);
                table = "";
    
                loadDataTable();
                //loadNewDataTablePagination();
            });

            $( "#asignarNumeroCajaAuditoria" ).on( "click", function() {
                
                $("#overlay").fadeIn(300);
                let valor_numero_caja_auditoria = $("#valor_numero_caja_auditoria").val();
                setTimeout(() => {
                    $('#modalAsignarNumeroCajaAuditoria').modal('hide');
                }, "300");
                $.ajax({
                    url: "{{url()->current()}}"+"/asignarNumeroCajaAuditoria",
                    method: "POST",
                    data: {
                        selected_table_items: JSON.stringify(selected_table_items),
                        valor_numero_caja_auditoria: valor_numero_caja_auditoria,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        $( "#buscarOficios" ).trigger( "click" );
                        //$("#overlay").fadeOut(300);
                    }
                });
            });

            $( "#asignarFechaEnvioAuditoria" ).on( "click", function() {
                
                $("#overlay").fadeIn(300);
                let valor_fecha_envio_auditoria = $("#valor_fecha_envio_auditoria").val();
                setTimeout(() => {
                    $('#modalAsignarFechaEnvioAuditoria').modal('hide');
                }, "300");
                $.ajax({
                    url: "{{url()->current()}}"+"/asignarFechaEnvioAuditoria",
                    method: "POST",
                    data: {
                        selected_table_items: JSON.stringify(selected_table_items),
                        valor_fecha_envio_auditoria: valor_fecha_envio_auditoria,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        $( "#buscarOficios" ).trigger( "click" );
                        //$("#overlay").fadeOut(300);
                    }
                });
            });

            $( "#cerrarNumeroCaja" ).on( "click", function() {
                
                $("#overlay").fadeIn(300);
                let valor_numero_caja = $("#valor_numero_caja").val();
                setTimeout(() => {
                    $('#modalCerrarCaja').modal('hide');
                }, "300");
                $.ajax({
                    url: "{{url()->current()}}"+"/cerrarNumeroCaja",
                    method: "POST",
                    data: {
                        valor_numero_caja: valor_numero_caja,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        $( "#buscarOficios" ).trigger( "click" );
                        //$("#overlay").fadeOut(300);
                    }
                });
            });

            $( "#asignarPorNumeroCaja" ).on( "click", function() {
                
                $("#overlay").fadeIn(300);
                let valor_numero_caja_por_numero_caja = $("#valor_numero_caja_por_numero_caja").val();
                let valor_numero_caja_auditoria_por_numero_caja = $("#valor_numero_caja_auditoria_por_numero_caja").val();
                let valor_fecha_envio_auditoria_por_numero_caja = $("#valor_fecha_envio_auditoria_por_numero_caja").val();
                setTimeout(() => {
                    $('#modalAsignarPorNumeroCaja').modal('hide');
                }, "300");
                $.ajax({
                    url: "{{url()->current()}}"+"/asignarPorNumeroCaja",
                    method: "POST",
                    data: {
                        valor_numero_caja: JSON.stringify(valor_numero_caja_por_numero_caja),
                        valor_numero_caja_auditoria: valor_numero_caja_auditoria_por_numero_caja,
                        valor_fecha_envio_auditoria: valor_fecha_envio_auditoria_por_numero_caja,
                        
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        $( "#buscarOficios" ).trigger( "click" );
                        //$("#overlay").fadeOut(300);
                    }
                });
            });

            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd"
            });

            $('[data-toggle="tooltip"]').tooltip();

        });

        function loadDataTable(){
            $("#valor_numero_caja_por_numero_caja").empty();
            $("#valor_numero_caja_por_numero_caja").html('');
            $("#valor_numero_caja").empty();
            $("#valor_numero_caja").html('');
            
            $.ajax({
                url: "{{url('/getOficiosByFilters')}}",
                method: "POST",
                data: {
                    fecha_registro_desde_search: $('#fecha_registro_desde_search').val(),
                    fecha_registro_hasta_search: $('#fecha_registro_hasta_search').val(),
                    tipo_id_search: JSON.stringify($('#tipo_id_search').val()),
                    ruc_search: $('#ruc_search').val(),
                    numero_establecimiento_search: $('#numero_establecimiento_search').val(),
                    razon_social_search: $('#razon_social_search').val(),
                    fecha_recepcion_desde_search: $('#fecha_recepcion_desde_search').val(),
                    fecha_recepcion_hasta_search: $('#fecha_recepcion_hasta_search').val(),
                    tipo_atencion_id_search: JSON.stringify($('#tipo_atencion_id_search').val()),
                    tipo_estado_caja_id_search: JSON.stringify($('#tipo_estado_caja_id_search').val()),
                    provincia_id_search: JSON.stringify($('#provincia_id_search').val()),
                    fecha_servicio_desde_search: $('#fecha_servicio_desde_search').val(),
                    fecha_servicio_hasta_search: $('#fecha_servicio_hasta_search').val(),
                    numero_casos_search: $('#numero_casos_search').val(),
                    monto_planilla_search: $('#monto_planilla_search').val(),
                    numero_caja_ant_search: $('#numero_caja_ant_search').val(),
                    numero_caja_search: $('#numero_caja_search').val(),
                    numero_caja_auditoria_search: $('#numero_caja_auditoria_search').val(),
                    fecha_envio_auditoria_desde_search: $('#fecha_envio_auditoria_desde_search').val(),
                    fecha_envio_auditoria_hasta_search: $('#fecha_envio_auditoria_hasta_search').val(),
                    institucion_id_search: JSON.stringify($('#institucion_id_search').val()),
                    documento_externo_search: $('#documento_externo_search').val(),
                    tipo_firma_id_search: JSON.stringify($('#tipo_firma_id_search').val()),
                    observacion_search: $('#observacion_search').val(),
                    numero_quipux_search: $('#numero_quipux_search').val(),
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
                dataType: 'json',
                success: function (response) {
                    $("#overlay").fadeOut(300);

                    $("#collapseTwo").collapse('show');
                    
                    oficios = response.oficios;
                    tipos = response.tipos;
                    tipos_atencion = response.tipos_atencion;
                    tipos_estado_caja = response.tipos_estado_caja;
                    provincias = response.provincias;
                    instituciones = response.instituciones;
                    tipos_firma = response.tipos_firma;
                    responsables = response.responsables;

                    oficios_files = response.oficiosFiles;

                    let selectHtmlCajasAbiertas = '<option value="">Seleccione una Caja:</option>';
                    $.each(response.cajasAbiertas, function (key, value) {
                        selectHtmlCajasAbiertas += '<option value="' + value.numero_caja + '">' + value.numero_caja + '</option>';
                    });

                    let selectHtmlCajasCerradas = '<option value="">Seleccione una Caja:</option>';
                    $.each(response.cajasCerradas, function (key, value) {
                        selectHtmlCajasCerradas += '<option value="' + value.numero_caja + '">' + value.numero_caja + '</option>';
                    });
                    $("#valor_numero_caja").html(selectHtmlCajasAbiertas);
                    $("#valor_numero_caja").selectpicker('render');
                    $("#valor_numero_caja_por_numero_caja").html(selectHtmlCajasCerradas);
                    $("#valor_numero_caja_por_numero_caja").selectpicker('render');
                    $(".selectpicker").selectpicker('refresh');

                    dataTableData.totalRegistros = 0;
                    dataTableData.totalNumCasos = 0;
                    dataTableData.totalMontoPlanilla = 0;

                    tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];

                    tableHeaderRef.insertRow().innerHTML = 
                        "<th>#</th>"+
                        "<th>Fecha Registro</th>"+
                        "<th>Ruc</th>"+
                        "<th># Establecimiento</th>"+
                        "<th>Razón Social</th>"+
                        "<th>Fecha Recepción</th>"+
                        "<th>Tipo de Atención</th>"+
                        "<th>Provincia</th>"+
                        "<th>Mes y Año del Servicio</th>"+
                        "<th>Número de Expedientes</th>"+
                        "<th>Monto Planilla</th>"+
                        "<th>Número de Caja Anterior</th>"+
                        "<th>Escoger</th>"+
                        "<th>Número de Caja</th>"+
                        "<th>Estado Caja</th>"+
                        "<th>Número de Caja Auditoría</th>"+
                        "<th>Fecha Envio Auditoría</th>"+
                        "<th>Tipo de Institución</th>"+
                        "<th>Documento Externo</th>"+
                        "<th>Tipo de Firma del Documento</th>"+
                        "<th>Observaciones</th>"+
                        "<th># de Quipux</th>"+
                        "<th>Estado Trámite</th>"+
                        "<th>Fecha Devolución Auditoria</th>"+
                        "<th>Observaciones Devolución Auditoria</th>"+
                        "<th>Fecha Devolución Prestador</th>"+
                        "<th>Observaciones Devolución Prestador</th>"+
                        "<th>Responsable</th>"+
                        "<th>Archivos</th>"+
                        "<th>Acción</th>";

                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

                    let contador = 1;
                    let meses = [{"id":"01","nombre":"Enero"},{"id":"02","nombre":"Febrero"},{"id":"03","nombre":"Marzo"},{"id":"04","nombre":"Abril"},{"id":"05","nombre":"Mayo"},{"id":"06","nombre":"Junio"},{"id":"07","nombre":"Julio"},{"id":"08","nombre":"Agosto"},{"id":"09","nombre":"Septiembre"},{"id":"10","nombre":"Octubre"},{"id":"11","nombre":"Noviembre"},{"id":"12","nombre":"Diciembre"}];
                    for (let oficio of oficios) {
                        
                        let rutaEdit = "{{url()->current()}}"+"/"+oficio.id+"/edit";
                        let rutaDelete = "{{url()->current()}}"+"/"+oficio.id;
                        let rutaDownloadFiles = "{{url('/files')}}"+"/";

                        let files_name = [];
                        let html_files = "";
                        let innerHTML = "";
                        let htmlEdit = "";
                        let htmlDelete = "";
                        let htmlDuplicar = "";
                        let htmlCheck = "";
                        let fs = [];
                        let mes = "";
                        let anio = "";
                        htmlEdit +=@if (auth()->user()->can('oficio.edit')) '<a class="btn btn-success text-white" href="'+rutaEdit+'">Editar</a>' @else '' @endif;
                        htmlDelete += @if (auth()->user()->can('oficio.delete')) '<a class="btn btn-danger text-white" href="javascript:void(0);" onclick="event.preventDefault(); deleteDialog('+oficio.id+')">Borrar</a> <form id="delete-form-'+oficio.id+'" action="'+rutaDelete+'" method="POST" style="display: none;">@method('DELETE')@csrf</form>' @else '' @endif;
                        htmlDuplicar += @if (auth()->user()->can('oficio.duplicar')) '<a class="btn btn-warning text-white" href="javascript:void(0);" onclick="event.preventDefault(); duplicarDialog('+oficio.id+')">Duplicar</a>' @else '' @endif;
                        htmlCheck += @if (auth()->user()->can('oficio.edit')) '<input type="checkbox" id="'+ oficio.id +'" name="select" class="checkSingle" onclick="toggle('+oficio.id+');">' @else '' @endif;
                        
                        if(typeof oficios_files[oficio.id] !== "undefined"){
                            oficios_files[oficio.id].forEach((file) => html_files += '<a href="'+rutaDownloadFiles+file.name+'" target="_blank" download> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>'+file.name+'</a>');
                        }
                        
                        fs = oficio.fecha_servicio.split("-");
                        if(fs.length > 0){
                            if(parseInt(fs[1]) > 0){
                                mes = meses.find(ms => ms.id === fs[1]);
                                mes = mes.nombre;
                                anio = fs[0];
                            }else{
                                mes = "";
                                anio = "";
                            }
                        }else{
                            mes = "";
                            anio = "";
                        }

                        innerHTML += 
                            "<td>"+ contador+ "</td>"+
                            "<td>"+ oficio.fecha_registro+ "</td>"+
                            "<td>"+ oficio.ruc+ "</td>"+
                            "<td>"+ oficio.numero_establecimiento+ "</td>"+
                            "<td>"+ oficio.razon_social+ "</td>"+
                            "<td>"+ oficio.fecha_recepcion+ "</td>"+
                            "<td>"+ oficio.tipo_atencion_nombre+ "</td>"+
                            "<td>"+ oficio.provincia_nombre+ "</td>"+
                            "<td>"+ mes + "-" + anio + "</td>"+
                            "<td>"+ oficio.numero_casos+ "</td>"+
                            "<td>"+ oficio.monto_planilla+ "</td>"+
                            "<td>"+ oficio.numero_caja_ant+ "</td>";
                            if(oficio.esCreadorRegistro || roles_user_auth_ids.includes(1)){
                                innerHTML +="<td>"+ htmlCheck +"</td>";
                            }else{
                                innerHTML += "<td></td>";
                            }
                            innerHTML +="<td>"+ oficio.numero_caja+ "</td>"+
                            "<td>"+ oficio.tipo_estado_caja_nombre+ "</td>"+
                            "<td>"+ oficio.numero_caja_auditoria+ "</td>"+
                            "<td>"+ oficio.fecha_envio_auditoria+ "</td>"+
                            "<td>"+ oficio.institucion_nombre+ "</td>"+
                            "<td>"+ oficio.documento_externo+ "</td>"+
                            "<td>"+ oficio.tipo_firma_nombre+ "</td>"+
                            "<td>"+ oficio.observaciones+ "</td>"+
                            "<td>"+ oficio.numero_quipux+ "</td>"+
                            "<td>"+ oficio.estado_tramite_nombre+ "</td>"+
                            "<td>"+ oficio.fecha_devolucion_auditoria+ "</td>"+
                            "<td>"+ oficio.observaciones_devolucion_auditoria+ "</td>"+
                            "<td>"+ oficio.fecha_devolucion_prestador+ "</td>"+
                            "<td>"+ oficio.observaciones_devolucion_prestador+ "</td>"+
                            "<td>"+ oficio.responsable_nombre+ "</td>"+
                            "<td>"+ html_files + "</td>";
                            if(oficio.esCreadorRegistro){
                                innerHTML +="<td>" + htmlEdit + htmlDelete + htmlDuplicar + "</td>";
                            }else{
                                innerHTML += "<td></td>";
                            }

                            tableRef.insertRow().innerHTML = innerHTML;
                            contador += 1;
                    }
                    
                    //if ($('#dataTable').length) {

                        
                        $('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
                        $('#dataTable thead tr:eq(1) th').each( function (i) {
                            
                            var title = $(this).text();
                            if(title !== '#' && title !== 'Escoger' && title !== 'Archivos' && title !== 'Acción'){
                                $(this).html( '<input type="text" placeholder="Buscar por: '+title+'" />' );

                                $( 'input', this ).on( 'keyup change', function () {
                                    if ( table.column(i).search() !== this.value ) {
                                        table
                                            .column(i)
                                            .search( this.value )
                                            .draw();
                                    }
                                    dataTableData.totalRegistros = 0;
                                    dataTableData.totalNumCasos = 0;
                                    dataTableData.totalMontoPlanilla = 0;
                                    
                                    getTotales(table.rows( { filter : 'applied'} ).data());
                                } );
                            }
                            if(title === 'Escoger'){
                                $(this).html( '<input type="checkbox" id="checkall" name="select">' );
                                $( '#checkall', this ).on( 'change', function () {

                                    let data = table.rows( { filter : 'applied'} ).data().$('input[type="checkbox"]');
                                    selected_table_items = [];
                                    let checked = this.checked;
                                    for (let i = 0; i < data.length; i++) {
                                        if (checked) {
                                            data[i].checked = checked;
                                            selected_table_items.push(data[i].id);
                                            
                                        } else {
                                            data[i].checked = checked;
                                        }
                                    }
                                } );
                            }
                            
                        } );

                        table = $('#dataTable').DataTable( {
                            scrollX: true,
                            orderCellsTop: true,
                            fixedHeader: true,
                            destroy: true,
                            paging: true,
                            searching: true,
                            autoWidth: true,
                            responsive: false,
                        });

                        getTotales(table.rows().data());
                    //}
                }
            });
        }

        function loadNewDataTablePagination(){
            $("#valor_numero_caja_por_numero_caja").empty();
            $("#valor_numero_caja_por_numero_caja").html('');
            $("#valor_numero_caja").empty();
            $("#valor_numero_caja").html('');

            table = $('#dataTable1').DataTable({
                scrollX: true,
                orderCellsTop: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: true,
                responsive: false,
                ajax: {
                    url: "{{url('/getOficiosByPagination')}}",
                    type: "POST",
                    data: function (data) {
                        data.search = $('input[type="search"]').val();
                        data.fecha_registro_desde_search = $('#fecha_registro_desde_search').val();
                        data.fecha_registro_hasta_search = $('#fecha_registro_hasta_search').val();
                        data.tipo_id_search = JSON.stringify($('#tipo_id_search').val());
                        data.ruc_search = $('#ruc_search').val();
                        data.numero_establecimiento_search = $('#numero_establecimiento_search').val();
                        data.razon_social_search = $('#razon_social_search').val();
                        data.fecha_recepcion_desde_search = $('#fecha_recepcion_desde_search').val();
                        data.fecha_recepcion_hasta_search = $('#fecha_recepcion_hasta_search').val();
                        data.tipo_atencion_id_search = JSON.stringify($('#tipo_atencion_id_search').val());
                        data.tipo_estado_caja_id_search = JSON.stringify($('#tipo_estado_caja_id_search').val());
                        data.provincia_id_search = JSON.stringify($('#provincia_id_search').val());
                        data.fecha_servicio_desde_search = $('#fecha_servicio_desde_search').val();
                        data.fecha_servicio_hasta_search = $('#fecha_servicio_hasta_search').val();
                        data.numero_casos_search = $('#numero_casos_search').val();
                        data.monto_planilla_search = $('#monto_planilla_search').val();
                        data.numero_caja_ant_search = $('#numero_caja_ant_search').val();
                        data.numero_caja_search = $('#numero_caja_search').val();
                        data.numero_caja_auditoria_search = $('#numero_caja_auditoria_search').val();
                        data.fecha_envio_auditoria_desde_search = $('#fecha_envio_auditoria_desde_search').val();
                        data.fecha_envio_auditoria_hasta_search = $('#fecha_envio_auditoria_hasta_search').val();
                        data.institucion_id_search = JSON.stringify($('#institucion_id_search').val());
                        data.documento_externo_search = $('#documento_externo_search').val();
                        data.tipo_firma_id_search = JSON.stringify($('#tipo_firma_id_search').val());
                        data.observacion_search = $('#observacion_search').val();
                        data.numero_quipux_search = $('#numero_quipux_search').val();
                        data.estado_tramite_id_search = JSON.stringify($('#estado_tramite_id_search').val()),
                        data.fecha_devolucion_auditoria_desde_search = $('#fecha_devolucion_auditoria_desde_search').val(),
                        data.fecha_devolucion_auditoria_hasta_search = $('#fecha_devolucion_auditoria_hasta_search').val(),
                        data.fecha_devolucion_prestador_desde_search = $('#fecha_devolucion_prestador_desde_search').val(),
                        data.fecha_devolucion_prestador_hasta_search = $('#fecha_devolucion_prestador_hasta_search').val(),
                        data.observacion_devolucion_auditoria_search = $('#observacion_devolucion_auditoria_search').val(),
                        data.observacion_devolucion_prestador_search = $('#observacion_devolucion_prestador_search').val(),
                        data.responsable_id_search = JSON.stringify($('#responsable_id_search').val());
                        data._token = '{{csrf_token()}}';
                    },dataSrc: function (response) {

                        total_registros = response.recordsFiltered;
                        total_numero_casos = response.totalNumeroCasos;
                        total_monto_planilla = response.totalMontoPlanilla;
                        
                        data = response.data;

                        let selectHtmlCajasAbiertas = '<option value="">Seleccione una Caja:</option>';
                        $.each(response.cajasAbiertas, function (key, value) {
                            selectHtmlCajasAbiertas += '<option value="' + value.numero_caja + '">' + value.numero_caja + '</option>';
                        });

                        let selectHtmlCajasCerradas = '<option value="">Seleccione una Caja:</option>';
                        $.each(response.cajasCerradas, function (key, value) {
                            selectHtmlCajasCerradas += '<option value="' + value.numero_caja + '">' + value.numero_caja + '</option>';
                        });

                        let tipos = response.tipos;
                        let tipos_atencion = response.tipos_atencion;
                        let tipos_estado_caja = response.tipos_estado_caja;
                        let provincias = response.provincias;
                        let instituciones = response.instituciones;
                        let tipos_firma = response.tipos_firma;
                        let estados_tramite = response.estados_tramite;
                        let responsables = response.responsables;
                        oficios_files = response.oficiosFiles;

                        $("#valor_numero_caja").html(selectHtmlCajasAbiertas);
                        $("#valor_numero_caja").selectpicker('render');
                        $("#valor_numero_caja_por_numero_caja").html(selectHtmlCajasCerradas);
                        $("#valor_numero_caja_por_numero_caja").selectpicker('render');
                        $(".selectpicker").selectpicker('refresh');
                        
                        let contador = 1;
                        for (const dt of data) {
                            
                            let fs = [];
                            let mes = "";
                            let anio = "";

                            fs = dt.fecha_servicio.split("-");
                            if(fs.length > 0){
                                if(parseInt(fs[1]) > 0){
                                    mes = meses.find(ms => ms.id === fs[1]);
                                    mes = mes.nombre;
                                    anio = fs[0];
                                }else{
                                    mes = "";
                                    anio = "";
                                }
                            }else{
                                mes = "";
                                anio = "";
                            }

                            dt.contador = contador;
                            dt.fecha_servicio = mes + "-" + anio;

                            /*dt.tipo_nombre = typeof tipos[dt.tipo_id] !== "undefined" ? tipos[dt.tipo_id] : "";
                            dt.tipo_atencion_nombre = typeof tipos_atencion[dt.tipo_atencion_id] !== "undefined" ? tipos_atencion[dt.tipo_atencion_id] : "";
                            dt.tipo_estado_caja_nombre = typeof tipos_estado_caja[dt.estado_caja_id] !== "undefined" ? tipos_estado_caja[dt.estado_caja_id] : "";
                            dt.provincia_nombre = typeof provincias[dt.provincia_id] !== "undefined" ? provincias[dt.provincia_id] : "";
                            dt.institucion_nombre = typeof instituciones[dt.institucion_id] !== "undefined" ? instituciones[dt.institucion_id] : "";
                            dt.tipo_firma_nombre = typeof tipos_firma[dt.tipo_firma_id] !== "undefined" ? tipos_firma[dt.tipo_firma_id] : "";
                            dt.estado_tramite_nombre = typeof estados_tramite[dt.estado_tramite_id] !== "undefined" ? estados_tramite[dt.estado_tramite_id] : "";
                            dt.responsable_nombre = typeof responsables[dt.responsable_id] !== "undefined" ? responsables[dt.responsable_id] : "";
                            dt.esCreadorRegistro = usuario_actual == dt.responsable_id ? true : false;*/
                            
                            contador++;
                        }
                        
                        $('#totalRegistros').html(total_registros);
                        $('#totalNumCasos').html(total_numero_casos);
                        $('#totalMontoPlanilla').html('$' + total_monto_planilla.toFixed(2));
                        $("#overlay").fadeOut(300);
                        $("#collapseTwo").collapse('show');

                        return data;
                    },
                    beforeSend: function() {
                        $("#overlay").fadeIn(100);
                    },
                    complete: function() {
                        $("#overlay").fadeOut(100);
                    }
                },
                order: ['0', 'DESC'],
                pageLength: 10,
                searching: false,
                processing: true,
                    columns: [
                    {
                        data: 'id',
                    },
                    {
                        data: 'fecha_registro',
                    },
                    {
                        data: 'tipo_nombre',
                    },
                    {
                        data: 'ruc',
                    },
                    {
                        data: 'numero_establecimiento',
                    },
                    {
                        data: 'razon_social',
                    },
                    {
                        data: 'fecha_recepcion',
                    },
                    {
                        data: 'tipo_atencion_nombre',
                    },
                    {
                        data: 'provincia_nombre',
                    },
                    {
                        data: 'fecha_servicio',
                    },
                    {
                        data: 'numero_casos',
                    },
                    {
                        data: 'monto_planilla',
                    },
                    {
                        data: 'numero_caja_ant',
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            let innerHTML = "";
                            let htmlCheck = "";                        
                            htmlCheck += @if (auth()->user()->can('oficio.edit')) '<input type="checkbox" id="'+ data.id +'" name="select" class="checkSingle" onclick="toggle('+data.id+');">' @else '' @endif;

                            if(data.esCreadorRegistro || roles_user_auth_ids.includes(1)){
                                innerHTML += htmlCheck;
                            }

                            return innerHTML;
                        }
                    },
                    {
                        data: 'numero_caja',
                    },
                    {
                        data: 'tipo_estado_caja_nombre',
                    },
                    {
                        data: 'numero_caja_auditoria',
                    },
                    {
                        data: 'fecha_envio_auditoria',
                    },
                    {
                        data: 'institucion_nombre',
                    },
                    {
                        data: 'documento_externo',
                    },
                    {
                        data: 'tipo_firma_nombre',
                    },
                    {
                        data: 'observaciones',
                    },
                    {
                        data: 'numero_quipux',
                    },
                    {
                        data: 'estado_tramite_nombre',
                    },
                    {
                        data: 'fecha_devolucion_auditoria',
                    },
                    {
                        data: 'observaciones_devolucion_auditoria',
                    },
                    {
                        data: 'fecha_devolucion_prestador',
                    },
                    {
                        data: 'observaciones_devolucion_prestador',
                    },
                    {
                        data: 'responsable_nombre',
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            let html_files = "";
                            let rutaDownloadFiles = "{{url('/files')}}"+"/";
                            if(typeof oficios_files[data.id] !== "undefined"){
                                oficios_files[data.id].forEach((file) => html_files += '<a href="'+rutaDownloadFiles+file.name+'" target="_blank" download> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>'+file.name+'</a>');
                            }
                            return html_files;
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {


                            let rutaEdit = "{{url()->current()}}"+"/"+data.id+"/edit";
                            let rutaDelete = "{{url()->current()}}"+"/"+data.id;

                            let innerHTML = "";
                            let htmlEdit = "";
                            let htmlDelete = "";
                            let htmlDuplicar = "";
                            
                            htmlEdit +=@if (auth()->user()->can('oficio.edit')) '<a class="btn btn-success text-white" href="'+rutaEdit+'">Editar</a>' @else '' @endif;
                            htmlDelete += @if (auth()->user()->can('oficio.delete')) '<a class="btn btn-danger text-white" href="javascript:void(0);" onclick="event.preventDefault(); deleteDialog('+data.id+')">Borrar</a> <form id="delete-form-'+data.id+'" action="'+rutaDelete+'" method="POST" style="display: none;">@method('DELETE')@csrf</form>' @else '' @endif;
                            htmlDuplicar += @if (auth()->user()->can('oficio.duplicar')) '<a class="btn btn-warning text-white" href="javascript:void(0);" onclick="event.preventDefault(); duplicarDialog('+data.id+')">Duplicar</a>' @else '' @endif;

                            if(data.esCreadorRegistro){
                                innerHTML = htmlEdit + htmlDelete + htmlDuplicar;
                            }

                            return innerHTML;
                        }
                    },
                    
                ]
            });
        }

        function deleteDialog(id){
            $.confirm({
                title: 'Eliminar',
                content: '¡Esta seguro de borrar este registro!. </br>¡Esta acción será irreversible!',
                buttons: {
                    confirm: function () {
                        $("#overlay").fadeIn(300);
                        $.ajax({
                            url: "{{url()->current()}}"+"/"+id,
                            method: "POST",
                            data: {
                                _method: 'DELETE',
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function (response) {
                                $( "#buscarOficios" ).trigger( "click" );
                            }
                        });
                    },
                    cancel: function () {
                        //$.alert('Canceled!');
                    }
                }
            });
        }

        function getTotales(dataTable){
            dataTableArray = dataTable.toArray();
            dataTableData.totalRegistros = dataTableArray.length;
            if (dataTableArray.length > 0) {
                dataTableData.totalNumCasos = table.column(11,{ search: "applied" }).data().reduce( function (a, b) { return parseInt(a) + parseInt(b); } );
                dataTableData.totalMontoPlanilla = table.column(12,{ search: "applied" }).data().reduce( function (a, b) { return parseFloat(a) + parseFloat(b); } );
            }
            
            $('#totalRegistros').html(dataTableData.totalRegistros);
            $('#totalNumCasos').html(dataTableData.totalNumCasos);
            $('#totalMontoPlanilla').html('$' + dataTableData.totalMontoPlanilla.toFixed(2));
        }

        function duplicarDialog(id){
            let rutaDuplicar = "{{url()->current()}}"+"/duplicar/"+id;
            $.confirm({
                title: 'Duplicar',
                content: '¡Esta seguro de duplicar este registro!. </br>',
                buttons: {
                    confirm: function () {
                        $("#overlay").fadeIn(300);
                        window.location.href = rutaDuplicar;
                    },
                    cancel: function () {
                        //$.alert('Canceled!');
                    }
                }
            });
        }

        function toggle(id) {
            let checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            selected_table_items = [];
            for (let i = 0; i < checkboxes.length; i++) {
                selected_table_items.push(checkboxes[i].id);
            }
        }
        
     </script>
@endsection