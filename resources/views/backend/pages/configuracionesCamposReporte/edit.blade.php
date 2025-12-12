
@extends('backend.layouts.master')

@section('title')
Editar Configuración Reporte - Panel Editar Configuración Reporte
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
                <h4 class="page-title pull-left">Editar Configuración Reporte</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.configuracionesCamposReporte.index') }}">Todos las Configuracines Reporte</a></li>
                    <li><span>Editar Configuración Reporte - {{ $configuracionCamposReporte->nombre }}</span></li>
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
                    <h4 class="header-title">Editar Configuración Reporte - {{ $configuracionCamposReporte->nombre }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.configuracionesCamposReporte.update', $configuracionCamposReporte->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" onkeyup="this.value = this.value.toUpperCase();" placeholder="Nombre" value="{{old('nombre', $configuracionCamposReporte->nombre)}}" required>
                                @error('nombre')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="proceso_id">Seleccionar Proceso:</label>
                                <select id="proceso_id" name="proceso_id" class="form-control selectpicker" data-live-search="true" required>
                                    <option value="">Seleccione un Proceso</option>
                                    @foreach ($procesos as $key => $value)
                                        <option value="{{ $value->id }}" {{ old('proceso_id', $configuracionCamposReporte->proceso_id) == $value->id ? 'selected' : '' }}>{{ $value->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="funcionario_id">Buscar por Usuario:</label>
                                <select id="funcionario_id" name="funcionario_id" class="form-control selectpicker" data-live-search="true" required>
                                    <option value="">Seleccione un Usuario</option>
                                    @foreach ($funcionarios as $key => $value)
                                        <option value="{{ $value->id }}" {{ old('funcionario_id', $configuracionCamposReporte->funcionario_id) == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="habilitar">Habilitar:</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="custom_check">
                                    <label class="custom-control-label" for="custom_check"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <table id="dataTable" class="text-center" style="font-size: 10px; width: 100%;">
                                    <thead class="bg-light text-capitalize">
                                        
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        <input type="hidden" id="habilitar" name="habilitar">
                        <input type="hidden" id="campos" name="campos">
                        <button type="submit" id="guardar" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.configuracionesCamposReporte.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        let habilitar = "{{$configuracionCamposReporte->habilitar}}";
        
        if(habilitar == true || habilitar == 1){
            $("#custom_check").prop( "checked", true );
            $('#habilitar').val(1);
        }else{
            $("#custom_check").prop( "checked", false );
            $('#habilitar').val(0);
        }

        $('#custom_check').change(function() {
            if(this.checked){
                $('#habilitar').val(1);
            }else{
                $('#habilitar').val(0);
            }
        });

        obj_campos = '{{json_encode($objCampos)}}';
        obj_campos = obj_campos.replace(/&quot;/g, '"');
        obj_campos = JSON.parse(obj_campos);
        $('#campos').val(JSON.stringify(obj_campos));

        $('#proceso_id').change(function() {
            $.ajax({
                url: "{{url('/getCamposPorProceso')}}",
                method: "POST",
                data: {
                    proceso_id: $('#proceso_id').val(),
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    $("#overlay").fadeOut(300);

                    obj_campos = response.objCampos;

                    $('#dataTable').empty();

                    var tabla = $('#dataTable');
                    var thead = $('<thead></thead>').appendTo(tabla);
                    var tbody = $('<tbody><tbody/>').appendTo(tabla);
                    table = "";
                    mostrarCampos();
                },
                error: function(jqXHR, textoEstado, errorEncontrado) {
                    console.error('Error en la solicitud, por favor vuelva a intentar.');
                }
            });
        });

        $('#proceso_id').change();

    })

    function mostrarCampos(){
        tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];

        tableHeaderRef.insertRow().innerHTML = 
            "<th>Mostrar</th>"+
            "<th>Proceso</th>"+
            "<th>Sección</th>"+
            "<th>Campo</th>"+
            "<th>Orden</th>";

        tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

        let contador = 1;
        let checked = '';
        for (let obj of obj_campos) {
            debugger;
            let innerHTML = "";
            
            if(obj['habilitado']){
                checked = 'checked';
            }

            innerHTML += 
                '<td><input class="form-check-input me-1" onchange=cambiarObjeto("' + obj["nombre_seccion"] + '","' + obj["campo"] + '","habilitado",this.checked) type="checkbox" ' + checked + '></td>'+
                '<td>' + obj["nombre_proceso"] + '</td>'+
                '<td>' + obj["nombre_seccion"] + '</td>'+
                '<td>' + obj["nombre_campo"] + '</td>'+
                '<td><input class="form-control input-sm" onchange=cambiarObjeto("' + obj["nombre_seccion"] + '","' + obj["campo"] + '","orden",this.value) type="text" value="' + obj["orden"] + '"></td>';

                tableRef.insertRow().innerHTML = innerHTML;
                contador += 1;
        }

        table = $('#dataTable').DataTable( {
            scrollX: true,
            orderCellsTop: true,
            fixedHeader: true,
            destroy: true,
            paging: true,
            searching: true,
            autoWidth: false,
            responsive: false,
            pageLength: 60,
        });
    }

    function cambiarObjeto(nombre_seccion,campo,atributo,valor){
        let obj_finded = obj_campos.find(obj => obj.campo === campo && obj.nombre_seccion === nombre_seccion);
        obj_finded[atributo] = valor;
        $('#campos').val(JSON.stringify(obj_campos));
    }
</script>
@endsection