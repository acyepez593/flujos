
@extends('backend.layouts.master')

@section('title')
Crear Trámite - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
    .input-sm {
        padding: 5px;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .custom-control {
        position: relative;
        z-index: 1;
        display: block;
        min-height: 1.5rem;
        padding-left: 1.5rem;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
        print-color-adjust: exact;
    }
    .custom-switch {
        padding-left: 2.25rem;
    }
    .custom-control-input {
        position: absolute;
        left: 0;
        z-index: -1;
        width: 1rem;
        height: 1.25rem;
        opacity: 0;
    }
    .custom-control-label {
        position: relative;
        margin-bottom: 0;
        vertical-align: top;
    }
    .custom-control-input:checked~.custom-control-label::before {
        color: #fff;
        border-color: #007bff;
        background-color: #007bff;
    }
    .custom-switch .custom-control-label::before {
        left: -2.25rem;
        width: 1.75rem;
        pointer-events: all;
        border-radius: .5rem;
    }
    .custom-control-label::before, .custom-file-label, .custom-select {
        transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .custom-control-label::before {
        position: absolute;
        top: .25rem;
        left: -1.5rem;
        display: block;
        width: 1rem;
        height: 1rem;
        pointer-events: none;
        content: "";
        background-color: #fff;
        border: 1px solid #adb5bd;
    }
    .custom-switch .custom-control-input:checked~.custom-control-label::after {
        background-color: #fff;
        -webkit-transform: translateX(.75rem);
        transform: translateX(.75rem);
    }
    .custom-switch .custom-control-label::after {
        top: calc(.25rem + 2px);
        left: calc(-2.25rem + 2px);
        width: calc(1rem - 4px);
        height: calc(1rem - 4px);
        background-color: #adb5bd;
        border-radius: .5rem;
        transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-transform .15s ease-in-out;
        transition: transform .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        transition: transform .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-transform .15s ease-in-out;
    }
    .custom-control-label::after {
        position: absolute;
        top: .25rem;
        left: -1.5rem;
        display: block;
        width: 1rem;
        height: 1rem;
        content: "";
        background: 50% / 50% 50% no-repeat;
    }
</style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Crear Trámite</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.procesos.index') }}">Todas mis Trámites</a></li>
                    <li><span>Crear Trámite</span></li>
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
                    <h4 class="header-title">Crear Nuevo Trámite</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ url('admin') }}/tramites/{{$proceso_id}}/create" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="creacionTramite"></div>
                        
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ url('admin') }}/secuenciaProcesos/{{$proceso_id}}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $(document).on("input", ".int-number", function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

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

        //creacion_tramite
        renderFormPorSecuenciaProceso();

        $('.datepicker').datepicker({
            language: 'es',
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
            endDate: 0
        });

        /*for (let campo of listaCampos) {
            if(campo.tipo_campo == 'date'){
                $('.datepicker input[id=' + campo.id + ']').datepicker({
                    language: 'es',
                    autoclose: true,
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    endDate: campo.campo.configuracion.date_field_max_legth
                });
            }
        }*/

    });

    function renderFormPorSecuenciaProceso(){
        let html_components = "";
        let listaCampos = '{{$listaCampos}}';
        listaCampos = listaCampos.replace(/&quot;/g, '"');
        listaCampos = JSON.parse(listaCampos);

        let camposPorSeccion = Object.groupBy(listaCampos, (campo) => campo.seccion_campo);
        console.log(camposPorSeccion);

        let catalogos = '{{$catalogos}}';
        catalogos = catalogos.replace(/&quot;/g, '"');
        catalogos = JSON.parse(catalogos);

        

        html_components += '<div class="accordion" id="accordion">';
        //html_components += '<div class="form-row">';
        for (let seccion in camposPorSeccion) {
            let count = 1;

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
                let long = camposPorSeccion[seccion].length;

                switch (campo.tipo_campo) {
                    case "text":
                        
                        if(campo.visible){
                            html_components += '<div class="form-group col-md-6 col-sm-12">';
                            html_components += '<label for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>'+
                                                '<div class="input-group mb-3">';

                            if(campo.editable && campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '">';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required readonly>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" readonly>';
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
                                html_components += '</div></div></div>';
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
                                html_components +='</div></div></div>';
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
                                html_components +='</div></div></div>';
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
                                html_components += '</div></div>';
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
                                html_components +='</div></div>';
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
            html_components += '</div></div></div>';
        }
        html_components += '</div>'
        $("#creacionTramite").append(html_components);
    }
    
</script>
@endsection