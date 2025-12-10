@extends('backend.layouts.master')

@section('title')
    {{ __('Configuración Reporte - Panel de Configuración Reporte') }}
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
                <h4 class="page-title pull-left">{{ __('Configuración Reporte') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('Todos las Configuracines Reporte') }}</span></li>
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
                                                <label for="nombre_search">Nombre</label>
                                                <input type="text" class="form-control" id="nombre_search" name="nombre_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="proceso_id_search">Buscar por Proceso:</label>
                                                <select id="proceso_id_search" name="proceso_id_search" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    <option value="">Seleccione un Proceso</option>
                                                    @foreach ($procesos as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="funcionario_id_search">Buscar por Funcionario:</label>
                                                <select id="funcionario_id_search" name="funcionario_id_search" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    <option value="">Seleccione un Funcionario</option>
                                                    @foreach ($funcionarios as $key => $value)
                                                        <option value="{{ $value->id }}" {{ Auth::user()->id == $value->id ? 'selected' : ''}}>{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="habilitar_search">Buscar por Habilitar:</label>
                                                <select id="habilitar_search" name="habilitar_search" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    <option value="">Seleccione un Estado</option>
                                                    @foreach ($opcionesHabilitar as $key => $value)
                                                        <option value="{{ $value['id'] }}">{{ $value['nombre'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" id="buscarConfiguracionesReporte" class="btn btn-primary mt-4 pr-4 pl-4">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Configuraciones Reportes
                                </button>
                            </h5>
                            </div>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <h4 class="header-title float-left">{{ __('Configuraciones Reporte') }}</h4>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('configuracionCamposReporte.create'))
                                            <a class="btn btn-primary text-white" href="{{ route('admin.configuracionesCamposReporte.create') }}">
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
        let dataTableData = {
            totalRegistros : 0,
            totalNumCasos : 0,
            totalMontoPlanilla : 0
        };
        let tableRef = "";
        let tableHeaderRef = "";
        let configuracionesCamposReporte = [];
        let funcionarios = [];

        $(document).ready(function() {

            $( "#buscarConfiguracionesReporte" ).on( "click", function() {
                $("#overlay").fadeIn(300);
                $('#dataTable').empty();

                var tabla = $('#dataTable');
                var thead = $('<thead></thead>').appendTo(tabla);
                var tbody = $('<tbody><tbody/>').appendTo(tabla);
                table = "";
    
                loadDataTable();
            });

        });

        function loadDataTable(){
            $.ajax({
                url: "{{url('/getConfiguracionesCamposReporteByFilters')}}",
                method: "POST",
                data: {
                    nombre_search: $('#nombre_search').val(),
                    proceso_id_search: JSON.stringify($('#proceso_id_search').val()),
                    funcionario_id_search: JSON.stringify($('#funcionario_id_search').val()),
                    habilitar_search: JSON.stringify($('#habilitar_search').val()),
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    $("#overlay").fadeOut(300);

                    $("#collapseTwo").collapse('show');
                    
                    configuracionesCamposReporte = response.configuracionesCamposReporte;
                    funcionarios = response.funcionarios;

                    tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];

                    tableHeaderRef.insertRow().innerHTML = 
                        "<th>#</th>"+
                        "<th>Nombre</th>"+
                        "<th>Proceso</th>"+
                        "<th>Funcionario</th>"+
                        "<th>Habilitado</th>"+
                        "<th>Campos</th>"+
                        "<th>Acción</th>";

                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

                    let contador = 1;
                    
                    for (let configuracionCamposReporte of configuracionesCamposReporte) {
                        
                        let rutaEdit = "{{url()->current()}}"+"/"+configuracionCamposReporte.id+"/edit";
                        let rutaDelete = "{{url()->current()}}"+"/"+configuracionCamposReporte.id;
                        let innerHTML = "";
                        let htmlEdit = "";
                        let htmlDelete = "";
                        let htmlDuplicar = "";
                        let habilitado = "NO";
                        let campos = JSON.parse(configuracionCamposReporte.campos);
                        let htmlCampos = '<ul style="text-align: left;">';

                        if(configuracionCamposReporte.habilitar == true || configuracionCamposReporte.habilitar == 1){
                            habilitado = "SI";
                        }

                        for (var objCampo of campos) {
                            let estado = (objCampo.habilitado) ? "Habilitado" : "Deshabilitado";
                            htmlCampos += '<li>' + objCampo.nombre_campo + ' : ' + estado + '</li>';
                        };
                        htmlCampos += '</ul>';
                        
                        
                        htmlEdit +=@if (auth()->user()->can('configuracionCamposReporte.edit')) '<a class="btn btn-success text-white" href="'+rutaEdit+'">Editar</a>' @else '' @endif;
                        htmlDelete += @if (auth()->user()->can('configuracionCamposReporte.delete')) '<a class="btn btn-danger text-white" href="javascript:void(0);" onclick="event.preventDefault(); deleteDialog('+configuracionCamposReporte.id+')">Borrar</a> <form id="delete-form-'+configuracionCamposReporte.id+'" action="'+rutaDelete+'" method="POST" style="display: none;">@method('DELETE')@csrf</form>' @else '' @endif;
                        htmlDuplicar += @if (auth()->user()->can('configuracionCamposReporte.duplicar')) '<a class="btn btn-warning text-white" href="javascript:void(0);" onclick="event.preventDefault(); duplicarDialog('+configuracionCamposReporte.id+')">Duplicar</a>' @else '' @endif;

                        innerHTML += 
                            "<td>"+ contador + "</td>"+
                            "<td>"+ configuracionCamposReporte.nombre + "</td>"+
                            "<td>"+ configuracionCamposReporte.proceso_nombre + "</td>"+
                            "<td>"+ configuracionCamposReporte.funcionario_nombre + "</td>"+
                            "<td>"+ habilitado + "</td>"+
                            "<td>"+ htmlCampos + "</td>"+
                            "<td>" + htmlEdit + htmlDelete + "</td>";

                            tableRef.insertRow().innerHTML = innerHTML;
                            contador += 1;
                    }
                    
                    //if ($('#dataTable').length) {

                        
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

                    //}
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
                                $( "#buscarConfiguracionesReporte" ).trigger( "click" );
                            }
                        });
                    },
                    cancel: function () {
                        //$.alert('Canceled!');
                    }
                }
            });
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
        
     </script>
@endsection