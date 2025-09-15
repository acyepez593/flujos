@extends('backend.layouts.master')

@section('title')
    {{ __('Secuencia Procesos - Panel de Secuencia Proceso') }}
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
                <h4 class="page-title pull-left">{{ __('Secuencia Procesos') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('Todas las Secuencias Procesos') }}</span></li>
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
                                                <label for="estatus_search">Buscar por Estatus:</label>
                                                <select id="estatus_search" name="estatus_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="ACTIVO" selected>ACTIVO</option>
                                                    <option value="INACTIVO">INACTIVO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tiempo_procesamiento_search">Buscar por Tiempo procesamiento</label>
                                                <input type="text" class="form-control int-number" id="tiempo_procesamiento_search" name="tiempo_procesamiento_search">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="actores_search">Buscar por Actor:</label>
                                                <select id="actores_search" name="actores_search" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">Seleccione un Actor</option>
                                                    @foreach ($actores as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="configuracion_search">Buscar por Configuración</label>
                                                <input type="text" class="form-control" id="configuracion_search" name="configuracion_search">
                                            </div>
                                        </div>
                                        <div class="form-row">
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

                                        <button type="button" id="buscarSecuenciaProcesos" class="btn btn-primary mt-4 pr-4 pl-4">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Secuencia Procesos
                                </button>
                            </h5>
                            </div>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <h4 class="header-title float-left">{{ __('Secuencia Procesos') }}</h4>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('proceso.create'))
                                            <a class="btn btn-primary text-white" href="{{ url('admin') }}/secuenciaProcesos/{{$proceso_id}}/create">
                                                {{ __('Crear Nueva') }}
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
        let secuenciaProcesos = [];
        let creadores = [];
        let proceso_id = '{{$proceso_id}}';

        $(document).ready(function() {

            $( "#buscarSecuenciaProcesos" ).on( "click", function() {
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
                url: "{{url('/getSecuenciaProcesosByFilters')}}/{{$proceso_id}}",
                method: "POST",
                data: {
                    nombre_search: $('#nombre_search').val(),
                    descripcion_search: $('#descripcion_search').val(),
                    estatus_search: JSON.stringify($('#estatus_search').val()),
                    tiempo_procesamiento_search: $('#tiempo_procesamiento_search').val(),
                    actores_search: JSON.stringify($('#actores_search').val()),
                    configuracion_search: JSON.stringify($('#configuracion_search').val()),
                    creado_por_search: JSON.stringify($('#creado_por_search').val()),
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    $("#overlay").fadeOut(300);

                    $("#collapseTwo").collapse('show');
                    
                    secuenciaProcesos = response.secuenciaProcesos;
                    creadores = response.creadores;

                    tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];

                    tableHeaderRef.insertRow().innerHTML = 
                        "<th>#</th>"+
                        "<th>Nombre</th>"+
                        "<th>Descripción</th>"+
                        "<th>Estatus</th>"+
                        "<th>Tiempo Procesamiento</th>"+
                        "<th>Actores</th>"+
                        "<th>Configuración</th>"+
                        "<th>Creador Por</th>"+
                        "<th>Acción</th>";

                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

                    let contador = 1;
                    for (let secuenciaProceso of secuenciaProcesos) {
                        
                        let rutaEdit = "{{url()->current()}}"+"/"+secuenciaProceso.id+"/edit";
                        let rutaDelete = "{{url()->current()}}"+"/"+secuenciaProceso.id;
                        let innerHTML = "";
                        let htmlEdit = "";
                        let htmlDelete = "";
                        htmlEdit +=@if (auth()->user()->can('proceso.edit')) '<a class="btn btn-success text-white" href="'+rutaEdit+'">Editar</a>' @else '' @endif;
                        htmlDelete += @if (auth()->user()->can('proceso.delete')) '<a class="btn btn-danger text-white" href="javascript:void(0);" onclick="event.preventDefault(); deleteDialog('+secuenciaProceso.id+')">Borrar</a> <form id="delete-form-'+secuenciaProceso.id+'" action="'+rutaDelete+'" method="POST" style="display: none;">@method('DELETE')@csrf</form>' @else '' @endif;

                        innerHTML += 
                            "<td>"+ contador+ "</td>"+
                            "<td>"+ secuenciaProceso.nombre+ "</td>"+
                            "<td>"+ secuenciaProceso.descripcion+ "</td>"+
                            "<td>"+ secuenciaProceso.estatus+ "</td>"+
                            "<td>"+ secuenciaProceso.tiempo_procesamiento+ "</td>"+
                            "<td>"+ secuenciaProceso.actores_nombre+ "</td>"+
                            "<td>"+ secuenciaProceso.estatus+ "</td>"+
                            "<td>"+ secuenciaProceso.configuracion+ "</td>"+
                            "<td>"+ secuenciaProceso.created_at+ "</td>";
                            if(secuenciaProceso.esCreadorRegistro){
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
                            url: "{{url()->current()}}"+"/"+id,
                            method: "POST",
                            data: {
                                _method: 'DELETE',
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function (response) {
                                $( "#buscarSecuenciaProcesos" ).trigger( "click" );
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