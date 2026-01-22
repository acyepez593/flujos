@extends('backend.layouts.master')

@section('title')
    {{ __('Trámites - Panel de Tramites') }}
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

        .timeline {
            background: linear-gradient(to right, transparent 0%, transparent 38px, rgb(230, 230, 230) 38px, rgb(230, 230, 230) 40px, transparent 40px, transparent 100%);
        }
        .timeline-icon {
            background-color: rgb(225, 228, 232);
            color: #788793;
        }
        .timeline-badge {
            width: 28px;
            height: 24px;
            margin-left: 0px;
            padding: 15px;
        }
        .align-items-center {
            align-items: center !important;
        }

        .justify-content-center {
            justify-content: center !important;
        }
        .fa-rotate-45 {
            transform: rotate(45deg);
        }
        .fa, .fas, .far, .fal, .fad, .fab {
            -moz-osx-font-smoothing: grayscale;
            -webkit-font-smoothing: antialiased;
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
        }
        .far {
            font-family: "FontAwesome";
            font-weight: 400;
        }

    </style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">{{ __('Trámites') }}</h4>
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
                <div class="modal-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#datos">Datos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#trazabilidad">Trazabilidad</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane container active" id="datos">
                            <div id="detalleTramite"></div>
                        </div>
                        <div class="tab-pane container fade" id="trazabilidad">
                            <div id="detalleTrazabilidad"></div>
                        </div>
                    </div>
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
        let rutaDownloadFiles = "{{url('/files')}}"+"/";

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

        });

        function loadDataTable(){
            $.ajax({
                url: "{{url('/getTramitesByFilters')}}",
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
                        "<th>Creado Por</th>"+
                        "<th>Creado En</th>"+
                        "<th>Acción</th>";

                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

                    let contador = 1;
                    for (let tramite of tramites) {
                        
                        let rutaView ="";
                        let rutaEdit = "{{url()->current()}}"+"/"+tramite.id+"/edit";
                        let rutaDelete = "{{url()->current()}}"+"/"+tramite.id;
                        let innerHTML = "";
                        let htmlView = "";
                        let htmlEdit = "";
                        let htmlDelete = "";
                        
                        htmlView +=@if (auth()->user()->can('tramite.view')) '<a class="icon-margin" title="Ver" style="color: #007bff; cursor:pointer;margin:5px;" onclick="javascript:void(0);mostrarDetalle('+ tramite.id +')"><i class="fa fa-eye fa-2x"></i></a>' @else '' @endif;
                        htmlEdit +=@if (auth()->user()->can('tramite.edit')) '<a class="btn btn-success text-white" href="'+rutaEdit+'">Editar</a>' @else '' @endif;
                        htmlDelete += @if (auth()->user()->can('tramite.delete')) '<a class="btn btn-danger text-white" href="javascript:void(0);" onclick="event.preventDefault(); deleteDialog('+tramite.id+')">Borrar</a> <form id="delete-form-'+tramite.id+'" action="'+rutaDelete+'" method="POST" style="display: none;">@method('DELETE')@csrf</form>' @else '' @endif;

                        innerHTML += 
                            "<td>"+ contador+ "</td>"+
                            "<td>"+ tramite.proceso_nombre+ "</td>"+
                            "<td>"+ tramite.secuencia_nombre+ "</td>"+
                            "<td>"+ tramite.funcionario_actual_nombre+ "</td>"+
                            "<td>"+ tramite.estatus+ "</td>"+
                            "<td>"+ tramite.creado_por_nombre+ "</td>"+
                            "<td>"+ moment(tramite.created_at).format("YYYY-MM-DD HH:mm")+ "</td>"+
                            "<td>" + htmlView + "</td>";
                            /*if(tramite.esCreadorRegistro){
                                innerHTML +="<td>" + htmlView + "</td>";
                            }else{
                                innerHTML += "<td></td>";
                            }*/

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
        let tramite = [];
        let datos = [];
        let trazabilidad = [];
        let files = [];

        function mostrarDetalle(tramite_id){

            $("#overlay").fadeIn(300);
            $("#detalleTramite").empty();
            $("#detalleTrazabilidad").empty();
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

                    files = response.files;
                    tramite = tramites.find(tramite => tramite.id === tramite_id);
                    datos = JSON.parse(tramite.datos);
                    
                    listaCampos = JSON.parse(response.listaCampos);
                    camposPorSeccion = Object.groupBy(listaCampos, (campo) => campo.seccion_campo);

                    trazabilidad = response.trazabilidad;
                    construirTrazabilidad(trazabilidad);

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
                            '<div class="card-body" style="pointer-events: none;">';
                            if(seccion == 'BENEFICIARIOS'){
                                for (let [index, beneficiario] of datos.data[seccion].entries()) {

                                    html_components += '<div id="beneficiario_' + (index + 1) + '" class="card">'+
                                    '<div class="card-header">'+
                                    'Beneficiario';
                                    
                                    html_components += '</div>'+
                                    '<div class="card-body" style="pointer-events: none;">';

                                    html_components += '<div class="form-row">';

                                    html_components += construirCampos(count,long,seccion,index);

                                    if((index+1) == datos.data[seccion].length){
                                        html_components += '</div></div></div>';
                                    }
                                }
                            }else{
                                html_components += '<div class="form-row">';

                                html_components += construirCampos(count,long,seccion);
                            
                                html_components += '</div>';
                            }
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

        function construirTrazabilidad(trazabilidad){
            let html_trazabilidad = '<div class="px-4 mb-2 timeline">';

            for (let traz of trazabilidad) {
                switch (traz.tipo) {
                    case "CREACION":
                        html_trazabilidad += '<div class="py-3 d-flex" read-only="true">'+
                            '<div class="badge timeline-badge mr-1 rounded d-flex justify-content-center align-items-center timeline-icon">'+
                                '<i class="far fa-circle fa-2x" style="color: blue;"></i>'+
                            '</div> '+
                            '<div class="flex-grow-1">'+
                                '<strong>'+ moment(traz.created_at).format("YYYY-MM-DD HH:mm")+'</strong> ' + traz.funcionario_actual_nombre + ' creó el trámite.'+
                            '</div>'+
                        '</div>';
                        break;
                    case "CAMBIO SECCION":
                        html_trazabilidad += '<div class="py-3 d-flex" read-only="true">'+
                            '<div class="badge timeline-badge mr-1 rounded d-flex justify-content-center align-items-center timeline-icon">'+
                                '<i class="far fa-square fa-2x"></i>'+
                            '</div>'+
                            '<div class="flex-grow-1">'+
                                '<strong>'+ moment(traz.created_at).format("YYYY-MM-DD HH:mm")+'</strong> ' + traz.funcionario_actual_nombre + ' recibió el trámite para completar la actividad "' + traz.secuencia_proceso_nombre + '"'+
                            '</div>'+
                        '</div>';
                        break;
                    case "CONDICIONAL":
                        html_trazabilidad += '<div class="py-3 d-flex" read-only="true">'+
                            '<div class="badge timeline-badge mr-1 rounded d-flex justify-content-center align-items-center timeline-icon">'+
                                '<i class="far fa-square fa-rotate-45 fa-2x"></i>'+
                            '</div>'+
                            '<div class="flex-grow-1">'+
                                '<strong>'+ moment(traz.created_at).format("YYYY-MM-DD HH:mm")+'</strong> ' + traz.funcionario_actual_nombre + ' Aprobar: Si'+
                            '</div>'+
                        '</div>';
                        break;
                    case "FINALIZACION":
                        html_trazabilidad += '<div class="py-3 d-flex" read-only="true">'+
                            '<div class="badge timeline-badge mr-1 rounded d-flex justify-content-center align-items-center timeline-icon">'+
                                '<i class="far fa-circle fa-2x" style="color: red;"></i>'+
                            '</div> '+
                            '<div class="flex-grow-1">'+
                                '<strong>'+ moment(traz.created_at).format("YYYY-MM-DD HH:mm")+'</strong> Finalizó el trámite.'+
                            '</div>'+
                        '</div>';
                        break;
                }
            }
            html_trazabilidad += '</div>';
            $("#detalleTrazabilidad").append(html_trazabilidad);
        }

        function construirCampos(count,long,seccion,beneficiario_id=null){
            let html_components = '';

            for (let campo of camposPorSeccion[seccion]) {
                let valor = '';
                if(seccion == 'BENEFICIARIOS'){
                    let valor_campo = '';
                    if(beneficiario_id !== null){
                        valor_campo = datos.data[seccion][beneficiario_id][campo.variable];
                    }
                    html_components += getCampos(count,long,seccion,campo,valor_campo);
                }else{
                    html_components += getCampos(count,long,seccion,campo,datos.data[campo.seccion_campo][campo.variable]);
                }
                if(campo.visible){
                    count ++;
                }
            }
            return html_components;
        }

        function getCampos(count,long,seccion,campo,valor_campo){
            let html_components = '';

            switch (campo.tipo_campo) {
                case "text":
                    
                    if(campo.visible){
                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>'+
                                            '<div class="input-group mb-3">';

                        html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + valor_campo + '" readonly>';
                        
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

                        html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + valor_campo + '" readonly>';

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

                        html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + valor_campo + '" readonly>';

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

                        html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + valor_campo + '" readonly>';

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
                        let file = files.find(f => f.seccion_campo === seccion && f.variable === campo.variable && f.name === valor_campo);

                        if(files.length > 0 && file != undefined){
                            html_components += '<div class="form-group col-md-6 col-sm-12" style="pointer-events: auto;">';
                            html_components += '<label for="' + campo.configuracion.file_field_name + '">' + campo.nombre + '</label>';
                            html_components += '<p><a href="'+rutaDownloadFiles+file.name+'" target="_blank" download> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>'+file.name+'</a></p>';
                        }else{
                            html_components += '<div class="form-group col-md-6 col-sm-12">';
                            html_components += '<label for="' + campo.configuracion.file_field_name + '">' + campo.nombre + '</label>';
                            html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + valor_campo + '" accept=".pdf" readonly>';
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

                        html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" readonly>';
                        for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                            if(typeof valor_campo !== 'undefined' && valor_campo !== null){
                                if(valor_campo == catalogo.id){
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
            return html_components;
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