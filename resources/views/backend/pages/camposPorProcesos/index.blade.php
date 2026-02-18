@extends('backend.layouts.master')

@section('title')
    {{ __('Campos por Proceso - Panel de Campos por Proceso') }}
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
                <h4 class="page-title pull-left">{{ __('Campos por Proceso') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('Todos los Campos por Proceso') }}</span></li>
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
                                                <label for="tipo_campo_search">Buscar por Tipo de Campo:</label>
                                                <select id="tipo_campo_search" name="tipo_campo_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="text">TEXTO</option>
                                                    <option value="textarea">ÁREA DE TEXTO</option>
                                                    <option value="hidden">OCULTO</option>
                                                    <option value="email">EMAIL</option>
                                                    <option value="number">NUMÉRICO</option>
                                                    <option value="date">FECHA</option>
                                                    <option value="datetime">FECHA Y HORA</option>
                                                    <option value="file">ARCHIVO</option>
                                                    <option value="select">SELECCIONABLE</option>
                                                    <option value="checkbox">CHECKBOX</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="nombre_search">Buscar por Nombre</label>
                                                <input type="text" class="form-control" id="nombre_search" name="nombre_search">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="variable_search">Buscar por Variable</label>
                                                <input type="text" class="form-control" id="variable_search" name="variable_search">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="seccion_campo_search">Buscar por Sección del Campo:</label>
                                                <select id="seccion_campo_search" name="seccion_campo_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="RECEPCION">RECEPCION</option>
                                                    <option value="SINIESTRO">SINIESTRO</option>
                                                    <option value="VICTIMA">VICTIMA</option>
                                                    <option value="VEHICULO">VEHICULO</option>
                                                    <option value="RECLAMANTE">RECLAMANTE</option>
                                                    <option value="BENEFICIARIOS">BENEFICIARIOS</option>
                                                    <option value="MEDICA">MEDICA</option>
                                                    <option value="PROCEDENCIA">PROCEDENCIA</option>
                                                    <option value="FINANCIERO">FINANCIERO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="estatus_search">Buscar por Estatus:</label>
                                                <select id="estatus_search" name="estatus_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="ACTIVO" selected>ACTIVO</option>
                                                    <option value="INACTIVO">INACTIVO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="creado_por_search">Buscar por Creador:</label>
                                                <select id="creado_por_search" name="creado_por_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Creador</option>
                                                    @foreach ($creadores as $key => $value)
                                                        <option value="{{ $value->id }}" {{ Auth::user()->id == $value->id ? 'selected' : ''}}>{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <button type="button" id="buscarCamposPorProcesos" class="btn btn-primary mt-4 pr-4 pl-4">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Campos por Proceso
                                </button>
                            </h5>
                            </div>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <h4 class="header-title float-left">{{ __('Campos por Proceso') }}</h4>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('pantalla.create'))
                                            <a class="btn btn-primary text-white" href="{{ url('admin') }}/camposPorProcesos/{{$proceso_id}}/create">
                                                {{ __('Crear Nuevo') }}
                                            </a>
                                        @endif
                                    </p>
                                    <div class="clearfix"></div>
                                    <div class="data-tables">
                                        
                                        <table id="dataTable" class="text-center">
                                            <thead class="bg-light text-capitalize">
                                                
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
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
        let camposPorProcesos = [];
        let creadores = [];

        $(document).ready(function() {

            $( "#buscarCamposPorProcesos" ).on( "click", function() {
                $("#overlay").fadeIn(300);
                $('#dataTable').empty();

                var tabla = $('#dataTable');
                var thead = $('<thead></thead>').appendTo(tabla);
                var tbody = $('<tbody><tbody/>').appendTo(tabla);
                table = "";
    
                loadDataTable();
            });

            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd"
            });

        });

        function loadDataTable(){
            $.ajax({
                url: "{{url('/getCamposPorProcesosByFilters')}}/{{$proceso_id}}",
                method: "POST",
                data: {
                    tipo_campo_search:  JSON.stringify($('#tipo_campo_search').val()),
                    nombre_search:$('#nombre_search').val(),
                    seccion_campo_search:  JSON.stringify($('#seccion_campo_search').val()),
                    estatus_search:  JSON.stringify($('#estatus_search').val()),
                    creado_por_search: JSON.stringify($('#creado_por_search').val()),
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    $("#overlay").fadeOut(300);

                    $("#collapseTwo").collapse('show');
                    
                    camposPorProcesos = response.camposPorProcesos;
                    creadores = response.creadores;

                    tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];

                    tableHeaderRef.insertRow().innerHTML = 
                        "<th>#</th>"+
                        "<th>Tipo Campo</th>"+
                        "<th>Nombre</th>"+
                        "<th>Variable</th>"+
                        "<th>Seccion Campo</th>"+
                        "<th>Estatus</th>"+
                        "<th>Creador Por</th>"+
                        "<th>Fecha Creación</th>"+
                        "<th>Acción</th>";

                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

                    let contador = 1;
                    for (let camposPorProceso of camposPorProcesos) {
                        
                        let rutaEdit = "{{url()->current()}}"+"/"+camposPorProceso.id+"/edit";
                        let rutaDelete = "{{url()->current()}}"+"/"+camposPorProceso.id;
                        let innerHTML = "";
                        let htmlEdit = "";
                        let htmlDelete = "";
                        htmlEdit +=@if (auth()->user()->can('pantalla.edit')) '<a class="btn btn-success text-white" href="'+rutaEdit+'">Editar</a>' @else '' @endif;
                        htmlDelete += @if (auth()->user()->can('pantalla.delete')) '<a class="btn btn-danger text-white" href="javascript:void(0);" onclick="event.preventDefault(); deleteDialog('+camposPorProceso.id+')">Borrar</a> <form id="delete-form-'+camposPorProceso.id+'" action="'+rutaDelete+'" method="POST" style="display: none;">@method('DELETE')@csrf</form>' @else '' @endif;

                        innerHTML += 
                            "<td>"+ contador+ "</td>"+
                            "<td>"+ camposPorProceso.tipo_campo+ "</td>"+
                            "<td>"+ camposPorProceso.nombre+ "</td>"+
                            "<td>"+ camposPorProceso.variable+ "</td>"+
                            "<td>"+ camposPorProceso.seccion_campo+ "</td>"+
                            "<td>"+ camposPorProceso.estatus+ "</td>"+
                            "<td>"+ camposPorProceso.creado_por_nombre+ "</td>"+
                            "<td>"+ moment(camposPorProceso.created_at).format("YYYY-MM-DD HH:mm")+ "</td>";
                            if(camposPorProceso.esCreadorRegistro){
                                innerHTML +="<td>" + htmlEdit + htmlDelete + "</td>";
                            }else{
                                innerHTML += "<td></td>";
                            }

                            tableRef.insertRow().innerHTML = innerHTML;
                            contador += 1;
                    }
                        
                    $('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
                    $('#dataTable thead tr:eq(1) th').each( function (i) {
                        
                        var title = $(this).text();
                        if(title !== '#' && title !== 'Acción'){
                            $(this).html( '<input type="text" placeholder="Buscar por: '+title+'" />' );

                            $( 'input', this ).on( 'keyup change', function () {
                                if ( table.column(i).search() !== this.value ) {
                                    table
                                        .column(i)
                                        .search( this.value )
                                        .draw();
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
                    
                },
                error: function(jqXHR, textoEstado, errorEncontrado) {
                    console.error('Error en la solicitud, por favor vuelva a intentar.');
                }
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
                            url: "{{url()->current()}}"+"/"+id+"/delete",
                            method: "POST",
                            data: {
                                _method: 'DELETE',
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function (response) {
                                $( "#buscarCamposPorProcesos" ).trigger( "click" );
                            }
                        });
                    },
                    cancel: function () {

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