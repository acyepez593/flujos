
@extends('backend.layouts.master')

@section('title')
Agregar Documentación al Trámite - Admin Panel
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
                <h4 class="page-title pull-left">Agregar Documentación al Trámite</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.procesos.index') }}">Todas mis Trámites</a></li>
                    <li><span>Agregar Documentación al Trámite</span></li>
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
                    <h4 class="header-title">Agregar Documentación al Trámite</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form id="form" action="{{ url('admin') }}/tramites/{{$tramite_id}}/createAdditional" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="creacionDocumentacionAdicionalAlTramite"></div>
                        
                        <input type="hidden" id="secuencia_proceso_id" name="secuencia_proceso_id">
                        <input type="hidden" id="datos" name="datos">
                        <input type="hidden" id="crearFun" name="crearFun" value="NO">
                        <button type="button" id="guardar" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        
                        @if ($proceso_id == 1)
                        <button type="button" id="guardarYCrearAdicionalAlFunerario" class="btn btn-warning mt-4 pr-4 pl-4" title="Guarda el addicional del Fallecimiento y crea el addicional del funerario con los mismos datos del formulario">Guardar Adicional y Crear Funerario</button>
                        @endif
                        
                        <a href="{{ url('admin') }}/procesos" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
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
    let selectorPadre = '';
    let selectorHijo = '';

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

        /*let selectorPadre = '';
        let selectorHijo = '';*/

        //Renderiza el tramite adicioanl
        if(tramite_fun_id != 0){
            renderFormAdicionalBasadoEnFallecimiento();
        }else{
            renderFormAdicional();
        }
        

        $('.datepicker').datepicker({
            language: 'es',
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
            endDate: 0
        });

        for (let index in catalogosRelacionadosVariables) {

            if(index == catalogosRelacionadosVariables.length-1){
                selectorPadre += 'select[name="'+ catalogosRelacionadosVariables[index] +'"]' ;
            }else{
                selectorPadre += 'select[name="'+ catalogosRelacionadosVariables[index] +'"],';
            }
        }

        $(selectorPadre).on("change", function() {
            let dataCatalogo = catalogosByCatalogoId[$(this).val()];
            let seccion = $(this).parents('.collapse').attr('id');

            if(dataCatalogo != undefined){
                let id = dataCatalogo[0].tipo_catalogo_id;
                let variable = camposPorSeccion[seccion].find(campo => campo.configuracion.select_field_tipo_catalogo == id).variable;

                selectorHijo = 'select[name="'+ variable +'"]';
                
                $(selectorHijo).selectpicker('destroy');
                $(selectorHijo).html('');
                $(selectorHijo).append('<option value="">Seleccione el Catálogo Relacionado</option>');
                $.each(dataCatalogo, function (key, value) {
                    $(selectorHijo).append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $(selectorHijo).selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }else{
                $(selectorHijo).selectpicker('destroy');
                $(selectorHijo).html('');
            }

        });

        $(selectorPadre).trigger("change");

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

        $('#guardar').click(function(){
            //validar

            //guardar
            for (let seccion in camposPorSeccion) {
                generarDataObjeto(seccion);
            }
            if(document.getElementById('form').reportValidity()){
                $('#form').submit();
            }
            
        });

        $('#guardarYCrearAdicionalAlFunerario').click(function(){
            $('#crearFun').val('SI');
            //validar

            //guardar
            for (let seccion in camposPorSeccion) {
                generarDataObjeto(seccion);
            }
            if(document.getElementById('form').reportValidity()){
                $('#form').submit();
            }
            
        });

    });

    let proceso_id = '{{$proceso_id}}';
    let secuencia_proceso_id = '{{$secuenciaProcesoId}}';

    $('#secuencia_proceso_id').val(secuencia_proceso_id);

    let objeto = {
        data: {}
    }
    let camposPorSeccion = [];

    let tiposCatalogos = '{{$tiposCatalogos}}';
    tiposCatalogos = tiposCatalogos.replace(/&quot;/g, '"');
    tiposCatalogos = JSON.parse(tiposCatalogos);
    
    let catalogos = '{{$catalogos}}';
    catalogos = catalogos.replace(/&quot;/g, '"');
    catalogos = JSON.parse(catalogos);

    let tramite_fun_id = '{{$tramite_fun_id}}';
    console.log('tramite_fun_id');
    console.log(tramite_fun_id);
    let datos = '{{$tramiteFun}}';
    
    datos = datos.replace(/&quot;/g, '"');
    datos = datos.replace(/\\/g , '\\\\');
    datos = JSON.parse(datos);

    let files = '{}';
    files = JSON.parse(files);

    let catalogosRelacionadosByTipoCatalogo = '{{$catalogosRelacionadosByTipoCatalogo}}';
    catalogosRelacionadosByTipoCatalogo = catalogosRelacionadosByTipoCatalogo.replace(/&quot;/g, '"');
    catalogosRelacionadosByTipoCatalogo = JSON.parse(catalogosRelacionadosByTipoCatalogo);

    let catalogosRelacionadosIds = [];
    let catalogosRelacionadosVariables = [];

    for (const key in catalogosRelacionadosByTipoCatalogo) {
        catalogosRelacionadosIds.push(key);
    }

    let catalogosByCatalogoId = '{{$catalogosByCatalogoId}}';
    catalogosByCatalogoId = catalogosByCatalogoId.replace(/&quot;/g, '"');
    catalogosByCatalogoId = JSON.parse(catalogosByCatalogoId);

    function renderFormAdicional(){
        let html_components = "";
        let listaCampos = '{{$listaCampos}}';
        listaCampos = listaCampos.replace(/&quot;/g, '"');
        listaCampos = JSON.parse(listaCampos);
debugger;
        camposPorSeccion = Object.groupBy(listaCampos, (campo) => campo.seccion_campo);
        console.log(camposPorSeccion);

        inicializarObjeto(camposPorSeccion);

        html_components += '<div class="accordion" id="accordion">';
        
        for (let seccion in camposPorSeccion) {
            let count = 1;
            let long = camposPorSeccion[seccion].filter(campo => campo.visible === true).length;
            let nombre_seccion = seccion;
            
            if(long > 0){
                html_components += '<div class="card">'+
                '<div class="card-header" id="headingOne">'+
                '<h5 class="mb-0">'+
                '<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#' + seccion + '" aria-expanded="true" aria-controls="' + seccion + '">INFORMACIÓN ' + nombre_seccion + '</button>'+
                '</h5>'+
                '</div>'+
                '<div id="' + seccion + '" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">'+
                '<div class="card-body">';
                
                html_components += '<div class="form-row">';

                html_components += contruirCampos(count,long,seccion);

                html_components += '</div>';
            }
        }
        html_components += '</div>'
        $("#creacionDocumentacionAdicionalAlTramite").append(html_components);
    }

    function renderFormAdicionalBasadoEnFallecimiento(){
        let html_components = "";
        let listaCampos = '{{$listaCampos}}';
        listaCampos = listaCampos.replace(/&quot;/g, '"');
        listaCampos = JSON.parse(listaCampos);

        camposPorSeccion = Object.groupBy(listaCampos, (campo) => campo.seccion_campo);

        inicializarObjeto(camposPorSeccion);

        html_components += '<div class="accordion" id="accordion">';
        
        for (let seccion in camposPorSeccion) {
            count = 1;
            let long = camposPorSeccion[seccion].filter(campo => campo.visible === true).length;
            let nombre_seccion = seccion;

            if(long > 0){
                html_components += '<div class="card">'+
                '<div class="card-header" id="headingOne">'+
                '<h5 class="mb-0">'+
                '<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#' + seccion + '" aria-expanded="true" aria-controls="' + seccion + '">INFORMACIÓN ' + nombre_seccion + '</button>'+
                '</h5>'+
                '</div>'+
                '<div id="' + seccion + '" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">'+
                '<div class="card-body">';

                html_components += '<div class="form-row">';

                html_components += construirCampos(count,long,seccion);
            
                html_components += '</div>';
                
            }
        }
        html_components += '</div>'
        $("#creacionDocumentacionAdicionalAlTramite").append(html_components);
        $(".selectpicker").selectpicker('refresh');
    }

    function contruirCampos(count,long,seccion){
        let html_components = '';
        for (let campo of camposPorSeccion[seccion]) {
            switch (campo.tipo_campo) {
                case "text":
                    
                    if(campo.visible){
                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>'+
                                            '<div class="input-group mb-3">';

                        if(campo.variable == 'numero_documento'){
                            if(campo.editable && campo.requerido){
                                html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '">';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required readonly>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" readonly>';
                            }
                        }else{
                            if(campo.editable && campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required>';
                            }else if(campo.editable && !campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '">';
                            }else if(!campo.editable && campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" required readonly>';
                            }else if(!campo.editable && !campo.requerido){
                                html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + campo.configuracion.text_field_value + '" readonly>';
                            }
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
                case "checkbox":
                    
                    if(campo.visible){
                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<div class="form-check">';

                        if(campo.editable && campo.requerido){
                            html_components += '<input type="checkbox" class="' + campo.configuracion.checkbox_field_class + '" placeholder="' + campo.configuracion.checkbox_field_placeholder + '" title="' + campo.configuracion.checkbox_field_helper_text + '" name="' + campo.configuracion.checkbox_field_name + '" value="' + campo.configuracion.checkbox_field_value + '" required>';
                            html_components += '<label class="form-check-label" for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<input type="checkbox" class="' + campo.configuracion.checkbox_field_class + '" placeholder="' + campo.configuracion.checkbox_field_placeholder + '" title="' + campo.configuracion.checkbox_field_helper_text + '" name="' + campo.configuracion.checkbox_field_name + '" value="' + campo.configuracion.checkbox_field_value + '">';
                            html_components += '<label class="form-check-label" for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<input type="checkbox" class="' + campo.configuracion.checkbox_field_class + '" placeholder="' + campo.configuracion.checkbox_field_placeholder + '" title="' + campo.configuracion.checkbox_field_helper_text + '" name="' + campo.configuracion.checkbox_field_name + '" value="' + campo.configuracion.checkbox_field_value + '" required readonly>';
                            html_components += '<label class="form-check-label" for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<input type="checkbox" class="' + campo.configuracion.checkbox_field_class + '" placeholder="' + campo.configuracion.checkbox_field_placeholder + '" title="' + campo.configuracion.checkbox_field_helper_text + '" name="' + campo.configuracion.checkbox_field_name + '" value="' + campo.configuracion.checkbox_field_value + '" readonly>';
                            html_components += '<label class="form-check-label" for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>';
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
                    }

                    break;
                case "textarea":
                    
                    if(campo.visible){
                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.textarea_field_name + '">' + campo.nombre + '</label>'+
                                            '<div class="input-group mb-3">';

                        if(campo.editable && campo.requerido){
                            html_components += '<textarea class="' + campo.configuracion.textarea_field_class + '" minlength="' + campo.configuracion.textarea_field_min_legth + '" maxlength="' + campo.configuracion.textarea_field_max_legth + '" placeholder="' + campo.configuracion.textarea_field_placeholder + '" title="' + campo.configuracion.textarea_field_helper_text + '" name="' + campo.configuracion.textarea_field_name + '" value="' + campo.configuracion.textarea_field_value + '" rows="' + campo.configuracion.textarea_field_rows + '" required></textarea>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<textarea class="' + campo.configuracion.textarea_field_class + '" minlength="' + campo.configuracion.textarea_field_min_legth + '" maxlength="' + campo.configuracion.textarea_field_max_legth + '" placeholder="' + campo.configuracion.textarea_field_placeholder + '" title="' + campo.configuracion.textarea_field_helper_text + '" name="' + campo.configuracion.textarea_field_name + '" value="' + campo.configuracion.textarea_field_value + '" rows="' + campo.configuracion.textarea_field_rows + '"></textarea>';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<textarea class="' + campo.configuracion.textarea_field_class + '" minlength="' + campo.configuracion.textarea_field_min_legth + '" maxlength="' + campo.configuracion.textarea_field_max_legth + '" placeholder="' + campo.configuracion.textarea_field_placeholder + '" title="' + campo.configuracion.textarea_field_helper_text + '" name="' + campo.configuracion.textarea_field_name + '" value="' + campo.configuracion.textarea_field_value + '" rows="' + campo.configuracion.textarea_field_rows + '" required readonly></textarea>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<textarea class="' + campo.configuracion.textarea_field_class + '" minlength="' + campo.configuracion.textarea_field_min_legth + '" maxlength="' + campo.configuracion.textarea_field_max_legth + '" placeholder="' + campo.configuracion.textarea_field_placeholder + '" title="' + campo.configuracion.textarea_field_helper_text + '" name="' + campo.configuracion.textarea_field_name + '" value="' + campo.configuracion.textarea_field_value + '" rows="' + campo.configuracion.textarea_field_rows + '" readonly></textarea>';
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
                    }

                    break;
            }
            //count ++;
        }
        return html_components;
    }

    function construirCampos(count,long,seccion){
        let html_components = '';
        
        for (let campo of camposPorSeccion[seccion]) {
            let valor = '';

            html_components += getCampos(count,long,seccion,campo,datos.data[campo.seccion_campo][campo.variable]);
            
            if(campo.visible){
                count ++;
            }
        }
        return html_components;
    }

    function getCampos(count,long,seccion,campo,valor_campo){
        let html_components = '';
        if (valor_campo === undefined) {
            valor_campo = '';
        }
        
        switch (campo.tipo_campo) {
            case "text":
                
                if(campo.visible){
                    html_components += '<div class="form-group col-md-6 col-sm-12">';
                    html_components += '<label for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>'+
                                        '<div class="input-group mb-3">';

                    if(campo.variable == 'numero_documento'){
                        if(campo.editable && campo.requerido){
                            html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + valor_campo + '" required>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<input type="text" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + valor_campo + '">';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<input type="text" style="pointer-events: none;" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + valor_campo + '" required readonly>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<input type="text" style="pointer-events: none;" onchange="consultarSCI('+seccion+',this)" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + valor_campo + '" readonly>';
                        }
                    }else{
                        if(campo.editable && campo.requerido){
                            html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + valor_campo + '" required>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<input type="text" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + valor_campo + '">';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<input type="text" style="pointer-events: none;" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + valor_campo + '" required readonly>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<input type="text" style="pointer-events: none;" class="' + campo.configuracion.text_field_class + '" minlength="' + campo.configuracion.text_field_min_legth + '" maxlength="' + campo.configuracion.text_field_max_legth + '" placeholder="' + campo.configuracion.text_field_placeholder + '" title="' + campo.configuracion.text_field_helper_text + '" name="' + campo.configuracion.text_field_name + '" value="' + valor_campo + '" readonly>';
                        }
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
                }

                break;
            case "date":

                if(campo.visible){
                    html_components += '<div class="form-group col-md-6 col-sm-12">';
                    html_components += '<label for="' + campo.configuracion.date_field_name + '">' + campo.nombre + '</label>'+
                                        '<div class="datepicker date input-group">';

                    if(campo.editable && campo.requerido){
                        html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + valor_campo + '" required>';
                    }else if(campo.editable && !campo.requerido){
                        html_components += '<input type="text" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + valor_campo + '">';
                    }else if(!campo.editable && campo.requerido){
                        html_components += '<input type="text" style="pointer-events: none;" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + valor_campo + '" required readonly>';
                    }else if(!campo.editable && !campo.requerido){
                        html_components += '<input type="text" style="pointer-events: none;" class="' + campo.configuracion.date_field_class + '" min="' + campo.configuracion.date_field_min_legth + '" max="' + campo.configuracion.date_field_max_legth + '" placeholder="' + campo.configuracion.date_field_placeholder + '" title="' + campo.configuracion.date_field_helper_text + '" name="' + campo.configuracion.date_field_name + '" value="' + valor_campo + '" readonly>';
                    }

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

                    if(campo.editable && campo.requerido){
                        html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + valor_campo + '" required>';
                    }else if(campo.editable && !campo.requerido){
                        html_components += '<input type="number" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + valor_campo + '">';
                    }else if(!campo.editable && campo.requerido){
                        html_components += '<input type="number" style="pointer-events: none;" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + valor_campo + '" required readonly>';
                    }else if(!campo.editable && !campo.requerido){
                        html_components += '<input type="number" style="pointer-events: none;" class="' + campo.configuracion.number_field_class + '" min="' + campo.configuracion.number_field_min + '" max="' + campo.configuracion.number_field_max + '" placeholder="' + campo.configuracion.number_field_placeholder + '" title="' + campo.configuracion.number_field_helper_text + '" name="' + campo.configuracion.number_field_name + '" value="' + valor_campo + '" readonly>';
                    }

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

                    if(campo.editable && campo.requerido){
                        html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + valor_campo + '" required>';
                    }else if(campo.editable && !campo.requerido){
                        html_components += '<input type="email" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + valor_campo + '">';
                    }else if(!campo.editable && campo.requerido){
                        html_components += '<input type="email" style="pointer-events: none;" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + valor_campo + '" required readonly>';
                    }else if(!campo.editable && !campo.requerido){
                        html_components += '<input type="email" style="pointer-events: none;" class="' + campo.configuracion.email_field_class + '" maxlength="' + campo.configuracion.email_field_max_legth + '" placeholder="' + campo.configuracion.email_field_placeholder + '" title="' + campo.configuracion.email_field_helper_text + '" name="' + campo.configuracion.email_field_name + '" value="' + valor_campo + '" readonly>';
                    }

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
                    //let file = files.find(f => f.seccion_campo === seccion && f.variable === campo.variable && f.name === valor_campo);

                    if(files.length > 0 && file != undefined){
                        let id = file.name.slice(0, -4);
                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.file_field_name + '">' + campo.nombre + '</label>';
                        html_components += '<div id="'+id+'"><i onclick="event.preventDefault(); deleteFile(\''+seccion+'\',\''+campo.configuracion.file_field_name+'\',\''+id+'\')" class="fa fa-trash fa-2x" title="Borrar" style="margin-left: 5px; margin-right:5px; cursor: pointer;"></i><a href="'+rutaDownloadFiles+file.name+'" target="_blank" download> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>'+file.name+'</a></div>';
                    }else{
                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.file_field_name + '">' + campo.nombre + '</label>';
                        
                        if(campo.editable && campo.requerido){
                            html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf" required>';
                        }else if(campo.editable && !campo.requerido){
                            html_components += '<input type="file" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf">';
                        }else if(!campo.editable && campo.requerido){
                            html_components += '<input type="file" style="pointer-events: none;" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf" required readonly>';
                        }else if(!campo.editable && !campo.requerido){
                            html_components += '<input type="file" style="pointer-events: none;" class="' + campo.configuracion.file_field_class + '" placeholder="' + campo.configuracion.file_field_placeholder + '" title="' + campo.configuracion.file_field_helper_text + '" name="' + campo.configuracion.file_field_name + '" value="' + campo.configuracion.file_field_value + '" accept=".pdf" readonly>';
                        }
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
                    if(campo.editable && campo.requerido){
                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.select_field_name + '">' + campo.nombre + '</label>';
                        html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" required>';
                        for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                            if(typeof valor_campo !== 'undefined' && valor_campo !== null){
                                if(valor_campo == catalogo.id){
                                    html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }else{
                                    html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }
                            }else{
                                //html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                            }
                        }
                        html_components += '</select>';
                    }else if(campo.editable && !campo.requerido){
                        html_components += '<div class="form-group col-md-6 col-sm-12">';
                        html_components += '<label for="' + campo.configuracion.select_field_name + '">' + campo.nombre + '</label>';
                        html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true">';
                        for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                            if(typeof valor_campo !== 'undefined' && valor_campo !== null){
                                if(valor_campo == catalogo.id){
                                    html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }else{
                                    html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }
                            }else{
                                //html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                            }
                        }
                        html_components += '</select>';
                    }else if(!campo.editable && campo.requerido){
                        html_components += '<div class="form-group col-md-6 col-sm-12" style="pointer-events: none;">';
                        html_components += '<label for="' + campo.configuracion.select_field_name + '">' + campo.nombre + '</label>';
                        html_components += '<select name="' + campo.configuracion.select_field_name + '" class="' + campo.configuracion.select_field_class + '" data-live-search="true" required readonly>';
                        for (let catalogo of catalogos[campo.configuracion.select_field_tipo_catalogo]) {
                            if(typeof valor_campo !== 'undefined' && valor_campo !== null){
                                if(valor_campo == catalogo.id){
                                    html_components += '<option selected value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }else{
                                    html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                                }
                            }else{
                                //html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                            }
                        }
                        html_components += '</select>';
                    }else if(!campo.editable && !campo.requerido){
                        html_components += '<div class="form-group col-md-6 col-sm-12" style="pointer-events: none;">';
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
                                //html_components += '<option value="' + catalogo.id + '">' + catalogo.nombre + '</option>';
                            }
                        }
                        html_components += '</select>';
                    }

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
            case "checkbox":
                let checked = '';
                if(valor_campo == true){
                    checked = 'checked';
                }
                if(campo.visible){
                    html_components += '<div class="form-group col-md-6 col-sm-12">';
                    html_components += '<div class="form-check">';

                    if(campo.editable && campo.requerido){
                        html_components += '<input type="checkbox" class="' + campo.configuracion.checkbox_field_class + '" placeholder="' + campo.configuracion.checkbox_field_placeholder + '" title="' + campo.configuracion.checkbox_field_helper_text + '" name="' + campo.configuracion.checkbox_field_name + '" value="" ' + checked + ' required>';
                        html_components += '<label class="form-check-label" for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>';
                    }else if(campo.editable && !campo.requerido){
                        html_components += '<input type="checkbox" class="' + campo.configuracion.checkbox_field_class + '" placeholder="' + campo.configuracion.checkbox_field_placeholder + '" title="' + campo.configuracion.checkbox_field_helper_text + '" name="' + campo.configuracion.checkbox_field_name + '" value="" ' + checked + ' >';
                        html_components += '<label class="form-check-label" for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>';
                    }else if(!campo.editable && campo.requerido){
                        html_components += '<input type="checkbox" style="pointer-events: none;" class="' + campo.configuracion.checkbox_field_class + '" placeholder="' + campo.configuracion.checkbox_field_placeholder + '" title="' + campo.configuracion.checkbox_field_helper_text + '" name="' + campo.configuracion.checkbox_field_name + '" value="" ' + checked + '  required readonly>';
                        html_components += '<label class="form-check-label" for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>';
                    }else if(!campo.editable && !campo.requerido){
                        html_components += '<input type="checkbox" style="pointer-events: none;" class="' + campo.configuracion.checkbox_field_class + '" placeholder="' + campo.configuracion.checkbox_field_placeholder + '" title="' + campo.configuracion.checkbox_field_helper_text + '" name="' + campo.configuracion.checkbox_field_name + '" value="" ' + checked + '  readonly>';
                            html_components += '<label class="form-check-label" for="' + campo.configuracion.text_field_name + '">' + campo.nombre + '</label>';
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
                }

                break;
            case "textarea":
                
                if(campo.visible){
                    html_components += '<div class="form-group col-md-6 col-sm-12">';
                    html_components += '<label for="' + campo.configuracion.textarea_field_name + '">' + campo.nombre + '</label>'+
                                        '<div class="input-group mb-3">';

                    if(campo.editable && campo.requerido){
                        html_components += '<textarea class="' + campo.configuracion.textarea_field_class + '" minlength="' + campo.configuracion.textarea_field_min_legth + '" maxlength="' + campo.configuracion.textarea_field_max_legth + '" placeholder="' + campo.configuracion.textarea_field_placeholder + '" title="' + campo.configuracion.textarea_field_helper_text + '" name="' + campo.configuracion.textarea_field_name + '" value="' + valor_campo + '" rows="' + campo.configuracion.textarea_field_rows + '" required>' + valor_campo + '</textarea>';
                    }else if(campo.editable && !campo.requerido){
                        html_components += '<textarea class="' + campo.configuracion.textarea_field_class + '" minlength="' + campo.configuracion.textarea_field_min_legth + '" maxlength="' + campo.configuracion.textarea_field_max_legth + '" placeholder="' + campo.configuracion.textarea_field_placeholder + '" title="' + campo.configuracion.textarea_field_helper_text + '" name="' + campo.configuracion.textarea_field_name + '" value="' + valor_campo + '" rows="' + campo.configuracion.textarea_field_rows + '">' + valor_campo + '</textarea>';
                    }else if(!campo.editable && campo.requerido){
                        html_components += '<textarea style="pointer-events: none;" class="' + campo.configuracion.textarea_field_class + '" minlength="' + campo.configuracion.textarea_field_min_legth + '" maxlength="' + campo.configuracion.textarea_field_max_legth + '" placeholder="' + campo.configuracion.textarea_field_placeholder + '" title="' + campo.configuracion.textarea_field_helper_text + '" name="' + campo.configuracion.textarea_field_name + '" value="' + valor_campo + '" rows="' + campo.configuracion.textarea_field_rows + '" required readonly>' + valor_campo + '</textarea>';
                    }else if(!campo.editable && !campo.requerido){
                        html_components += '<textarea style="pointer-events: none;" class="' + campo.configuracion.textarea_field_class + '" minlength="' + campo.configuracion.textarea_field_min_legth + '" maxlength="' + campo.configuracion.textarea_field_max_legth + '" placeholder="' + campo.configuracion.textarea_field_placeholder + '" title="' + campo.configuracion.textarea_field_helper_text + '" name="' + campo.configuracion.textarea_field_name + '" value="' + valor_campo + '" rows="' + campo.configuracion.textarea_field_rows + '" readonly>' + valor_campo + '</textarea>';
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
                }

                break;
        }
        return html_components;
    }

    function consultarSCI(seccion_campo, input){
        
        let seccion = seccion_campo.id;
        let numero_documento = input.value;
        let respuestaWS = [];

        if(numero_documento.length >= 10){
            $.ajax({
                url: "{{url('/consultarSCI')}}",
                type: "POST",
                data: {
                    tipo_consulta_id: 1,
                    identificacion: numero_documento,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    
                    respuestaWS = response.respuestaWs;
                    let nombre_completo = respuestaWS.find(dato => dato.campo === 'nombre').valor;
                    let estado_civil = respuestaWS.find(dato => dato.campo === 'estadoCivil').valor;
                    let sexo = respuestaWS.find(dato => dato.campo === 'sexo').valor;
                    let genero = '';
                    let fecha_nacimiento = respuestaWS.find(dato => dato.campo === 'fechaNacimiento').valor;
                    fecha_nacimiento = moment(fecha_nacimiento,'DD/MM/YYYY').format("YYYY-MM-DD");
                    let edad = calcularEdad(fecha_nacimiento);

                    $('#' + seccion + ' input[name="nombre_completo"]').val(nombre_completo);
                    if(sexo == 'HOMBRE'){
                        genero = 'MASCULINO';
                    }else if(sexo == 'MUJER'){
                        genero = 'FEMENINO';
                    }

                    $('#' + seccion + ' select[name="genero_id"] option').filter(function() {
                        return $(this).text() === genero;
                    }).prop('selected', true);
                    $('#' + seccion + ' select[name="genero_id"]').trigger("change");

                    $('#' + seccion + ' select[name="estado_civil_id"] option').filter(function() {
                        return $(this).text().includes(estado_civil);
                    }).prop('selected', true);
                    $('#' + seccion + ' select[name="estado_civil_id"]').trigger("change");

                    $('#' + seccion + ' input[name="fecha_nacimiento"]').datepicker("setDate",fecha_nacimiento);

                    $('#' + seccion + ' input[name="edad"]').val(edad);
                    
                }
            });
        }
        
    }

    function calcularEdad(fecha_de_nacimiento) {
        let fecha_actual = moment();
        let fecha_nacimiento = moment(fecha_de_nacimiento,'YYYY-MM-DD');
        let edad = moment.duration(fecha_actual.diff(fecha_nacimiento));

        return edad.years();
    }

    function inicializarObjeto(camposPorSeccion){
        for (let seccion in camposPorSeccion) {
            objeto.data[seccion] = {};
            for (let campo of camposPorSeccion[seccion]) {
                objeto.data[seccion][campo.variable] = "";
                if(catalogosRelacionadosIds.includes(campo.configuracion.select_field_tipo_catalogo)){
                    catalogosRelacionadosVariables.push(campo.variable);
                }
            }
            
        }
    }

    function generarDataObjeto(seccion){
        $('[id="' + seccion + '"]').find("input, select").each(function() {
            if($(this).attr('name') != undefined){
                if($(this).attr('type') == 'checkbox'){
                    objeto.data[seccion][$(this).attr('name')] = this.checked;
                }else{
                    objeto.data[seccion][$(this).attr('name')] = $(this).val();
                }
            }
        });

        $('[id="' + seccion + '"]').find("textarea").each(function() {
            if($(this).attr('name') != undefined){
                objeto.data[seccion][$(this).attr('name')] = $(this).val();
            }
        });

        $('#datos').val(JSON.stringify(objeto));
        
    }
    
</script>
@endsection