@extends('backend.layouts.master')

@section('title')
    {{ __('Bandeja de Trámites - Panel de Tramites') }}
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
                <h4 class="page-title pull-left">{{ __('Bandeja de Trámites') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('Todas los Trámites') }}</span></li>
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
                                                <label for="proceso_search">Buscar por Procesos:</label>
                                                <select id="proceso_search" name="proceso_search" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">Seleccione un Proceso</option>
                                                    @foreach ($procesos as $key => $value)
                                                        <option value="{{ $value->id }}" {{ ($key) == 0 ? 'selected' : '' }}>{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="estatus_search">Buscar por Estatus:</label>
                                                <select id="estatus_search" name="estatus_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="INGRESADO" selected>INGRESADO</option>
                                                    <option value="EN PROCESO DAP">EN PROCESO DAP</option>
                                                    <option value="EN PROCESO AUDITORIA">EN PROCESO AUDITORIA</option>
                                                    <option value="EN PROCESO FINANCIERO">EN PROCESO FINANCIERO</option>
                                                    <option value="PAGADO">PAGADO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="funcionario_search">Buscar por Funcionario:</label>
                                                <select id="funcionario_search" name="funcionario_search" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">Seleccione un Funcionario</option>
                                                    @foreach ($funcionarios as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="creado_por_search">Buscar por Creador:</label>
                                                <select id="creado_por_search" name="creado_por_search" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">Seleccione un Creador</option>
                                                    @foreach ($creadores as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <button type="button" id="buscarTramites" class="btn btn-primary mt-4 pr-4 pl-4">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Trámites
                                </button>
                            </h5>
                            </div>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <h4 class="header-title float-left">{{ __('Trámites') }}</h4>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('tramite.create'))
                                            <a id="crearTramite" class="btn btn-primary text-white" href="{{ url('admin') }}/tramites/">
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
        <!-- Modal Ver Detalle -->
        <div class="modal fade" id="modalVerDetalle" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Detalle Trámite</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detalleTramite">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
        let tableRef = "";
        let tableHeaderRef = "";
        let tramites = [];
        let creadores = [];

        $(document).ready(function() {

            $( "#buscarTramites" ).on( "click", function() {
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

            $('#proceso_search').on('change', function() {
                let selectedValue = $(this).val();
                $('#crearTramite').attr('href', "{{ url('admin') }}/tramites/"+selectedValue+"/create");
            });

            $('#proceso_search').trigger("change");

        });

        function loadDataTable(){
            $.ajax({
                url: "{{url('/getBandejaTramitesByFilters')}}",
                method: "POST",
                data: {
                    proceso_search: $('#proceso_search').val(),
                    estatus_search: JSON.stringify($('#estatus_search').val()),
                    funcionario_search: JSON.stringify($('#funcionario_search').val()),
                    creado_por_search: JSON.stringify($('#creado_por_search').val()),
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    $("#overlay").fadeOut(300);

                    $("#collapseTwo").collapse('show');
                    
                    tramites = response.tramites;
                    creadores = response.creadores;

                    tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];

                    tableHeaderRef.insertRow().innerHTML = 
                        "<th>#</th>"+
                        "<th>Proceso</th>"+
                        "<th>Actividad Actual</th>"+
                        "<th>Funcionario encargado</th>"+
                        "<th>Estatus</th>"+
                        "<th>Creador Por</th>"+
                        "<th>Creador En</th>"+
                        "<th>Acción</th>";

                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

                    let contador = 1;
                    for (let tramite of tramites) {
                        
                        let rutaView ="";
                        let rutaEdit = "{{url('admin')}}"+"/tramites/"+tramite.id+"/edit";
                        let rutaDelete = "{{url('admin')}}"+"/tramites/"+tramite.id;
                        let innerHTML = "";
                        let htmlView = "";
                        let htmlEdit = "";
                        let htmlDelete = "";
                        
                        htmlView +=@if (auth()->user()->can('tramite.view')) '<a class="icon-margin" title="Ver" style="color: #007bff; cursor:pointer;margin:5px;" onclick="javascript:void(0);mostarDetalle('+ tramite.id +')"><i class="fa fa-eye fa-2x"></i></a>' @else '' @endif;
                        htmlEdit +=@if (auth()->user()->can('tramite.edit')) '<a class="icon-margin" title="Editar" href="'+rutaEdit+'"><i class="fa fa-edit fa-2x"></i></a>' @else '' @endif;
                        htmlDelete += @if (auth()->user()->can('tramite.delete')) '<a class="btn btn-danger text-white" href="javascript:void(0);" onclick="event.preventDefault(); deleteDialog('+tramite.id+')">Borrar</a> <form id="delete-form-'+tramite.id+'" action="'+rutaDelete+'" method="POST" style="display: none;">@method('DELETE')@csrf</form>' @else '' @endif;

                        innerHTML += 
                            "<td>"+ contador+ "</td>"+
                            "<td>"+ tramite.proceso_nombre+ "</td>"+
                            "<td>"+ tramite.secuencia_nombre+ "</td>"+
                            "<td>"+ tramite.funcionario_actual_nombre+ "</td>"+
                            "<td>"+ tramite.estatus+ "</td>"+
                            "<td>"+ tramite.creado_por_nombre+ "</td>"+
                            "<td>"+ tramite.created_at+ "</td>";
                            if(tramite.esEditorRegistro){
                                innerHTML +="<td>" + htmlView + htmlEdit + "</td>";
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

        let html_components = "";
        let listaCampos = [];
        let catalogos = '{{$catalogos}}';
        catalogos = catalogos.replace(/&quot;/g, '"');
        catalogos = JSON.parse(catalogos);

        let camposPorSeccion = Object.groupBy(listaCampos, (campo) => campo.seccion_campo);

        function mostarDetalle(tramite_id){

            $("#overlay").fadeIn(300);
            $("#detalleTramite").empty();
            html_components = '';
            $.ajax({
                url: "{{url('/getListaCamposByTramite')}}",
                method: "POST",
                data: {
                    tramite_id: tramite_id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {

                    let tramite = tramites.find(tramite => tramite.id === tramite_id);
                    let datos = JSON.parse(tramite.datos);
                    
                    listaCampos = JSON.parse(response.listaCampos);
                    camposPorSeccion = Object.groupBy(listaCampos, (campo) => campo.seccion_campo);

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
                            '<div class="card-body">'+
                            '<div class="form-row">';

                            for (let campo of camposPorSeccion[seccion]) {
                            

                                switch (campo.tipo_campo) {
                                    case "text":
                                        
                                        if(campo.visible){
                                            html_components += '<div class="form-group col-md-6 col-sm-12">';
                                            html_components += '<label for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>'+
                                                                '<div class="input-group mb-3">';

                                            html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + datos.data[campo.seccion_campo][campo.variable] + '" readonly>';
                                            
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

                                            html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + datos.data[campo.seccion_campo][campo.variable] + '" readonly>';

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

                                            html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + datos.data[campo.seccion_campo][campo.variable] + '" readonly>';

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

                                            html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + datos.data[campo.seccion_campo][campo.variable] + '" readonly>';

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

                                            html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + datos.data[campo.seccion_campo][campo.variable] + '" accept=".pdf" readonly>';

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

                                            html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" readonly>';
                                            for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                                                if(typeof datos.data[campo.seccion_campo][campo.variable] !== 'undefined' && datos.data[campo.seccion_campo][campo.variable] !== null){
                                                    if(datos.data[campo.seccion_campo][campo.variable] == catalogo.id){
                                                        html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                                    }else{
                                                        html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                                    }
                                                }else{
                                                    html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                                }
                                            }
                                            html_components += '</select>';

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
                            html_components += '</div>';
                        }
                    }
                    html_components += '</div>'
                    $("#detalleTramite").append(html_components);
                    $(".selectpicker").selectpicker('refresh');
                    $("#overlay").fadeOut(300);
                    $("#modalVerDetalle").modal('show');

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