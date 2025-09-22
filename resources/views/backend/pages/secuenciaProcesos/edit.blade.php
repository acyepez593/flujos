
@extends('backend.layouts.master')

@section('title')
Editar Secuencia Proceso - Panel Secuencia Proceso
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
                <h4 class="page-title pull-left">Editar Secuencia Proceso</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ url('admin') }}/secuenciaProcesos/{{$proceso_id}}"">Todos las Secuencias Procesos</a></li>
                    <li><span>Editar Proceso - {{ $secuenciaProceso->nombre }}</span></li>
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
                    <h4 class="header-title">Editar Secuencia Proceso - {{ $secuenciaProceso->nombre }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ url('admin') }}/secuenciaProcesos/{{$proceso_id}}/{{$secuenciaProceso->id}}/edit" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Nombre</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $secuenciaProceso->nombre) }}" required>
                                    @error('nombre')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="descripcion">Descripción</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" value="{{ old('descripcion', $secuenciaProceso->descripcion) }}" required>
                                    @error('descripcion')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="estatus">Seleccione un Estatus:</label>
                                <select id="estatus" name="estatus" class="form-control selectpicker @error('estatus') is-invalid @enderror" data-live-search="true" required>
                                    <option value="ACTIVO" {{ old('estatus', $secuenciaProceso->estatus) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                                    <option value="INACTIVO" {{ old('estatus', $secuenciaProceso->estatus) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                                </select>
                                @error('estatus')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tiempo_procesamiento">Tiempo procesamiento</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control int-number @error('tiempo_procesamiento') is-invalid @enderror" id="tiempo_procesamiento" name="tiempo_procesamiento" value="{{ old('tiempo_procesamiento', $secuenciaProceso->tiempo_procesamiento) }}" required>
                                    @error('tiempo_procesamiento')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="actores">Seleccione un Actor:</label>
                                <select id="actores" name="actores" class="form-control selectpicker @error('actores') is-invalid @enderror" data-live-search="true" required>
                                    <option value="">Seleccione un Actor</option>
                                    @foreach ($actores as $key => $value)
                                        <option value="{{ $key }}" {{ old('actores', $secuenciaProceso->actores) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('actores')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="requiere_evaluacion">Requiere evaluación?:</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="requiere_evaluacion">
                                    <label class="custom-control-label" for="requiere_evaluacion"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row campos_con_evaluacion">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="pregunta_evaluacion">Pregunta Evaluación</label>
                                <input type="text" class="form-control @error('pregunta_evaluacion') is-invalid @enderror" onchange="generarConfiguracionObjeto('pregunta_evaluacion',this.value)" id="pregunta_evaluacion" name="pregunta_evaluacion" value="{{ old('pregunta_evaluacion') }}">
                                @error('pregunta_evaluacion')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="variable_evaluacion">Seleccione la variable a evaluar</label>
                                <select id="variable_evaluacion" onchange="generarConfiguracionObjeto('variable_evaluacion',this.value)" name="variable_evaluacion" class="form-control selectpicker @error('variable_evaluacion') is-invalid @enderror" data-live-search="true">
                                    <option value="">Seleccione la variable a evaluar</option>
                                    @foreach ($campos as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('variable_evaluacion')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row campos_con_evaluacion">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="camino_evaluacion_verdadero">Secuencia en caso de evaluación verdadera</label>
                                <select id="camino_evaluacion_verdadero" onchange="generarConfiguracionObjeto('camino_evaluacion_verdadero',this.value)" name="camino_evaluacion_verdadero" class="form-control selectpicker @error('camino_evaluacion_verdadero') is-invalid @enderror" data-live-search="true">
                                    <option value="">Secuencia en caso de evaluación verdadera</option>
                                    @foreach ($listaActividades as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('camino_evaluacion_verdadero')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="camino_evaluacion_falso">Secuencia en caso de evaluación falsa</label>
                                <select id="camino_evaluacion_falso" onchange="generarConfiguracionObjeto('camino_evaluacion_falso',this.value)" name="camino_evaluacion_falso" class="form-control selectpicker @error('camino_evaluacion_falso') is-invalid @enderror" data-live-search="true">
                                    <option value="">Secuencia en caso de evaluación falsa</option>
                                    @foreach ($listaActividades as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('camino_evaluacion_falso')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row campos_sin_evaluacion">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="camino_sin_evaluacion">Seleccione la siguiente secuencia</label>
                                <select id="camino_sin_evaluacion" onchange="generarConfiguracionObjeto('camino_sin_evaluacion',this.value)" name="camino_sin_evaluacion" class="form-control selectpicker @error('camino_sin_evaluacion') is-invalid @enderror" data-live-search="true">
                                    <option value="">Seleccione la siguiente secuencia</option>
                                    @foreach ($listaActividades as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('camino_sin_evaluacion')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <h4 class="header-title">Configuración de campos</h4>
                        
                        <div class="data-tables">
                            
                            <table id="configuracion_campos_table" class="table text-center">
                                <thead class="bg-light text-capitalize">
                                    <th>Sección Campo</th>
                                    <th>Nombre Campo</th>
                                    <th>Variable</th>
                                    <th>Editable</th>
                                    <th>Visible</th>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                        
                        <input type="hidden" id="configuracion" name="configuracion">
                        <input type="hidden" id="configuracion_campos" name="configuracion_campos">
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

        $('#requiere_evaluacion').change(function() {
            if(this.checked){
                $('.campos_con_evaluacion').show();
                $('.campos_sin_evaluacion').hide();
            }else{
                $('.campos_con_evaluacion').hide();
                $('.campos_sin_evaluacion').show();
            }
            generarConfiguracionObjeto('requiere_evaluacion',this.checked);
        });

        $('#requiere_evaluacion').change();
        $('#configuracion_campos').val(JSON.stringify(listaCampos));

        tableRef = document.getElementById('configuracion_campos_table').getElementsByTagName('tbody')[0];

        for (let campo of listaCampos) {
            debugger;
            let innerHTML = "";
            innerHTML += 
                "<td>"+ campo.seccion_campo+ "</td>"+
                "<td>"+ campo.nombre+ "</td>"+
                "<td>"+ campo.variable+ "</td>";
                if(campo.editable == true){
                    innerHTML +="<td><input class='form-check-input' type='checkbox' id='" + campo.seccion_campo + campo.variable + "_editble' checked></td>";
                }else{
                    innerHTML +="<td><input class='form-check-input' type='checkbox' id='" + campo.seccion_campo + campo.variable + "_editble'></td>";
                }
                if(campo.visible == true){
                    innerHTML += "<td><input class='form-check-input' type='checkbox' id='" + campo.seccion_campo + campo.variable + "_visible' checked></td>";
                }else{
                    innerHTML += "<td><input class='form-check-input' type='checkbox' id='" + campo.seccion_campo + campo.variable + "_visible'></td>";
                }

                tableRef.insertRow().innerHTML = innerHTML;
        }

        /*table = $('#configuracion_campos_table').DataTable( {
                        scrollX: true,
                        orderCellsTop: true,
                        fixedHeader: true,
                        destroy: true,
                        paging: true,
                        searching: true,
                        autoWidth: true,
                        responsive: false,
                    });*/
    })

    let table = "";
    let tableRef = "";
    
    let objeto = {
        requiere_evaluacion: false,
        pregunta_evaluacion: "",
        variable_evaluacion: "",
        camino_evaluacion_verdadero: "",
        camino_evaluacion_falso: "",
        camino_sin_evaluacion: ""
    }
    let listaCampos = '{{$listaCampos}}';
    listaCampos = listaCampos.replace(/&quot;/g, '"');
    listaCampos = JSON.parse(listaCampos);

    function generarConfiguracionObjeto(campo,valor){
        objeto[campo] = valor;
        $('#configuracion').val(JSON.stringify(objeto));
    }

    function generarConfiguracionCamposObjeto(id,obj){
        let campo = listaCampos.find(campo => campo.id === id);
        if(obj.id == id + '_editable'){
            campo.editable = obj.checked;
        }else{
            campo.visible = obj.checked;
        }
        
        $('#configuracion_campos').val(JSON.stringify(listaCampos));
    }
</script>
@endsection