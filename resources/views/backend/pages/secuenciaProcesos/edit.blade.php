
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
                    <li><a href="{{ url('admin') }}/secuenciaProcesos/{{$proceso_id}}">Todos las Secuencias Procesos</a></li>
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

                        <h4 class="header-title">Configuración de Correo</h4>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="habilitar_envio">Habilitar Envio?:</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" onchange="generarConfiguracionObjetoCorreo('habilitar_envio',this.checked)" class="custom-control-input" id="habilitar_envio">
                                    <label class="custom-control-label" for="habilitar_envio"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="subject">Subject</label>
                                <div class="input-group mb-3">
                                    <input type="text" onchange="generarConfiguracionObjetoCorreo('subject',this.value)" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="cc">CC</label>
                                <div class="input-group mb-3">
                                    <input type="text" onchange="generarConfiguracionObjetoCorreo('cc',this.value)" class="form-control @error('cc') is-invalid @enderror" id="cc" name="subject" value="{{ old('cc') }}" required>
                                    @error('cc')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="contenido_html" class="form-label">Contenido Html del Correo</label>
                                <textarea onchange="generarConfiguracionObjetoCorreo('contenido_html',this.value)" class="form-control" id="contenido_html" name="contenido_html" rows="6" value="old('contenido_html', $contenido_html)"></textarea>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <h4 class="header-title">Configuración de campos</h4>
                        
                        <div class="data-tables">
                            
                            <table id="configuracion_campos_table" class="table text-center">
                                <thead class="bg-light text-capitalize">
                                    <th>Tipo Campo</th>
                                    <th>Sección Campo</th>
                                    <th>Nombre Campo</th>
                                    <th>Variable</th>
                                    <th>Requerido</th>
                                    <th>Editable</th>
                                    <th>Visible</th>
                                    <th>Acción</th>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                        
                        <input type="hidden" id="configuracion" name="configuracion">
                        <input type="hidden" id="configuracion_campos" name="configuracion_campos">
                        <input type="hidden" id="configuracion_correo" name="configuracion_correo">
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ url('admin') }}/secuenciaProcesos/{{$proceso_id}}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
                    </form>
                    <!-- Modal Actualizar campo tipo texto -->
                    <div class="modal fade" id="modalActualizarCampoTipoTexto" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Campo de Tipo Texto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="text_field_label">Label</label>
                                        <input type="text" class="form-control" id="text_field_label" value="" readonly>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="text_field_value">Value</label>
                                        <input type="text" class="form-control" id="text_field_value" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="text_field_helper_text">Helper</label>
                                        <input type="text" class="form-control" id="text_field_helper_text" value="">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="text_field_placeholder">Placeholder</label>
                                        <input type="text" class="form-control" id="text_field_placeholder" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="text_field_class">Class</label>
                                        <input type="text" class="form-control" id="text_field_class" value="form-control">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="text_field_name">Name</label>
                                        <input type="text" class="form-control" id="text_field_name" value="" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="text_field_min_legth">Min Length</label>
                                        <input type="number" minlength="1" class="form-control" id="text_field_min_legth" value="">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="text_field_max_legth">Max Length</label>
                                        <input type="number" minlength="1" class="form-control" id="text_field_max_legth" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" id="actualizarConfiguracionDetalladaCampoTexto" class="btn btn-primary">Actualizar</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Actualizar campo tipo fecha -->
                    <div class="modal fade" id="modalActualizarCampoTipoFecha" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Campo de Tipo Fecha</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="date_field_label">Label</label>
                                        <input type="text" class="form-control" id="date_field_label" value="" readonly>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="date_field_value">Value</label>
                                        <input type="date" class="form-control" id="date_field_value" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="date_field_helper_text">Helper</label>
                                        <input type="text" class="form-control" id="date_field_helper_text" value="">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="date_field_placeholder">Placeholder</label>
                                        <input type="text" class="form-control" id="date_field_placeholder" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="date_field_class">Class</label>
                                        <input type="text" class="form-control" id="date_field_class" value="form-control">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="date_field_name">Name</label>
                                        <input type="text" class="form-control" id="date_field_name" value="" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="date_field_min_legth">Min</label>
                                        <input type="number" minlength="1" class="form-control" id="date_field_min_legth" value="">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="date_field_max_legth">Max</label>
                                        <input type="number" minlength="1" class="form-control" id="date_field_max_legth" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" id="actualizarConfiguracionDetalladaCampoFecha" class="btn btn-primary">Actualizar</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Actualizar campo tipo numérico -->
                    <div class="modal fade" id="modalActualizarCampoTipoNumerico" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Campo de Tipo Numérico</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="number_field_label">Label</label>
                                        <input type="text" class="form-control" id="number_field_label" value="" readonly>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="number_field_value">Value</label>
                                        <input type="number" class="form-control" id="number_field_value" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="number_field_helper_text">Helper</label>
                                        <input type="text" class="form-control" id="number_field_helper_text" value="">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="number_field_placeholder">Placeholder</label>
                                        <input type="text" class="form-control" id="number_field_placeholder" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="number_field_class">Class</label>
                                        <input type="text" class="form-control" id="number_field_class" value="form-control">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="number_field_name">Name</label>
                                        <input type="text" class="form-control" id="number_field_name" value="" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="number_field_min">Min</label>
                                        <input type="number" minlength="1" class="form-control" id="number_field_min" value="">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="number_field_max">Max</label>
                                        <input type="number" minlength="1" class="form-control" id="number_field_max" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="number_field_step">Step</label>
                                        <input type="number" minlength="1" class="form-control" id="number_field_step" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" id="actualizarConfiguracionDetalladaCampoNumerico" class="btn btn-primary">Actualizar</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Actualizar campo tipo email -->
                    <div class="modal fade" id="modalActualizarCampoTipoEmail" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Campo de Tipo Numérico</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="email_field_label">Label</label>
                                        <input type="text" class="form-control" id="email_field_label" value="" readonly>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="email_field_value">Value</label>
                                        <input type="email" class="form-control" id="email_field_value" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="email_field_helper_text">Helper</label>
                                        <input type="text" class="form-control" id="email_field_helper_text" value="">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="email_field_placeholder">Placeholder</label>
                                        <input type="text" class="form-control" id="email_field_placeholder" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="email_field_class">Class</label>
                                        <input type="text" class="form-control" id="email_field_class" value="form-control">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="email_field_name">Name</label>
                                        <input type="text" class="form-control" id="email_field_name" value="" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="email_field_max_legth">Max Legth</label>
                                        <input type="number" minlength="1" class="form-control" id="email_field_max_legth" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" id="actualizarConfiguracionDetalladaCampoEmail" class="btn btn-primary">Actualizar</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Actualizar campo tipo file -->
                    <div class="modal fade" id="modalActualizarCampoTipoFile" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Campo de Tipo Archivo</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="file_field_label">Label</label>
                                        <input type="text" class="form-control" id="file_field_label" value="" readonly>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="file_field_helper_text">Helper</label>
                                        <input type="text" class="form-control" id="file_field_helper_text" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="file_field_placeholder">Placeholder</label>
                                        <input type="text" class="form-control" id="file_field_placeholder" value="">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="file_field_class">Class</label>
                                        <input type="text" class="form-control" id="file_field_class" value="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="file_field_name">Name</label>
                                        <input type="text" class="form-control" id="file_field_name" value="" readonly>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="file_field_multiple_file_upload">Premitir multiples archivos:</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="file_field_multiple_file_upload">
                                            <label class="custom-control-label" for="file_field_multiple_file_upload"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" id="actualizarConfiguracionDetalladaCampoFile" class="btn btn-primary">Actualizar</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Actualizar campo tipo select -->
                    <div class="modal fade" id="modalActualizarCampoTipoSelect" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Campo de Tipo Seleccionable</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="select_field_label">Label</label>
                                        <input type="text" class="form-control" id="select_field_label" value="" readonly>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="select_field_tipo_catalogo">Seleccione un Tipo de Catálogo:</label>
                                        <select id="select_field_tipo_catalogo" name="select_field_tipo_catalogo" class="form-control selectpicker" data-live-search="true">
                                            <option value="">Seleccione un Tipo de Catálogo:</option>
                                            @foreach ($tiposCatalogos as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="select_field_default_value">Default Value</label>
                                        <select id="select_field_default_value" name="select_field_default_value" class="form-control selectpicker" data-live-search="true">
                                            
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="select_field_helper_text">Helper</label>
                                        <input type="text" class="form-control" id="select_field_helper_text" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="select_field_placeholder">Placeholder</label>
                                        <input type="text" class="form-control" id="select_field_placeholder" value="">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="select_field_class">Class</label>
                                        <input type="text" class="form-control" id="select_field_class" value="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="select_field_name">Name</label>
                                        <input type="text" class="form-control" id="select_field_name" value="" readonly>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="select_field_multiple_selection">Permitir selección múltiple:</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="select_field_multiple_selection">
                                            <label class="custom-control-label" for="select_field_multiple_selection"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" id="actualizarConfiguracionDetalladaCampoSelect" class="btn btn-primary">Actualizar</button>
                            </div>
                            </div>
                        </div>
                    </div>
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
        $('#configuracion_correo').val(JSON.stringify(objetoCorreo));

        tableRef = document.getElementById('configuracion_campos_table').getElementsByTagName('tbody')[0];

        for (let campo of listaCampos) {
            let innerHTML = "";
            innerHTML += 
                "<td>"+ campo.tipo_campo+ "</td>"+
                "<td>"+ campo.seccion_campo+ "</td>"+
                "<td>"+ campo.nombre+ "</td>"+
                "<td>"+ campo.variable+ "</td>";
                if(campo.requerido == true){
                    innerHTML +="<td><input class='form-check-input' type='checkbox' id='" + campo.id + "_requerido' onchange='generarConfiguracionCamposObjeto(" + campo.id + ",this)' checked></td>";
                }else{
                    innerHTML +="<td><input class='form-check-input' type='checkbox' id='" + campo.id + "_requerido' onchange='generarConfiguracionCamposObjeto(" + campo.id + ",this)'></td>";
                }
                if(campo.editable == true){
                    innerHTML +="<td><input class='form-check-input' type='checkbox' id='" + campo.id + "_editable' onchange='generarConfiguracionCamposObjeto(" + campo.id + ",this)' checked></td>";
                }else{
                    innerHTML +="<td><input class='form-check-input' type='checkbox' id='" + campo.id + "_editable' onchange='generarConfiguracionCamposObjeto(" + campo.id + ",this)'></td>";
                }
                if(campo.visible == true){
                    innerHTML += "<td><input class='form-check-input' type='checkbox' id='" + campo.id + "_visible' onchange='generarConfiguracionCamposObjeto(" + campo.id + ",this)' checked></td>";
                }else{
                    innerHTML += "<td><input class='form-check-input' type='checkbox' id='" + campo.id + "_visible' onchange='generarConfiguracionCamposObjeto(" + campo.id + ",this)'></td>";
                }

                innerHTML += "<td><div class='icon-margin' title='Confirugar Campo' onclick='getField(" + campo.id + ")'><i class='fa fa-cog fa-2x'></i></div></td>";

                tableRef.insertRow().innerHTML = innerHTML;
        }

        $("#actualizarConfiguracionDetalladaCampoTexto").click(function() {
            $("#modalActualizarCampoTipoTexto").find("input").each(function(index, element) {
                var valorInput = $(this).val();
                conf[element.id] = valorInput;
            });
            
            let campo = listaCampos.find(campo => campo.id === campo_id);
            campo.configuracion = conf;
            conf = {};
            $('#configuracion_campos').val(JSON.stringify(listaCampos));
            $('#modalActualizarCampoTipoTexto').modal('hide');
        });

        $("#actualizarConfiguracionDetalladaCampoFecha").click(function() {
            $("#modalActualizarCampoTipoFecha").find("input").each(function(index, element) {
                var valorInput = $(this).val();
                conf[element.id] = valorInput;
            });
            
            let campo = listaCampos.find(campo => campo.id === campo_id);
            campo.configuracion = conf;
            conf = {};
            $('#configuracion_campos').val(JSON.stringify(listaCampos));
            $('#modalActualizarCampoTipoFecha').modal('hide');
        });

        $("#actualizarConfiguracionDetalladaCampoNumerico").click(function() {
            $("#modalActualizarCampoTipoNumerico").find("input").each(function(index, element) {
                var valorInput = $(this).val();
                conf[element.id] = valorInput;
            });
            
            let campo = listaCampos.find(campo => campo.id === campo_id);
            campo.configuracion = conf;
            conf = {};
            $('#configuracion_campos').val(JSON.stringify(listaCampos));
            $('#modalActualizarCampoTipoNumerico').modal('hide');
        });

        $("#actualizarConfiguracionDetalladaCampoEmail").click(function() {
            $("#modalActualizarCampoTipoEmail").find("input").each(function(index, element) {
                var valorInput = $(this).val();
                conf[element.id] = valorInput;
            });
            
            let campo = listaCampos.find(campo => campo.id === campo_id);
            campo.configuracion = conf;
            conf = {};
            $('#configuracion_campos').val(JSON.stringify(listaCampos));
            $('#modalActualizarCampoTipoEmail').modal('hide');
        });

        $("#actualizarConfiguracionDetalladaCampoFile").click(function() {
            $("#modalActualizarCampoTipoFile").find("input").each(function(index, element) {
                if(element.id == 'file_field_multiple_file_upload'){
                    var valorInput = element.checked;
                }else{
                    var valorInput = $(this).val();
                }
                conf[element.id] = valorInput;
            });
            
            let campo = listaCampos.find(campo => campo.id === campo_id);
            campo.configuracion = conf;
            conf = {};
            $('#configuracion_campos').val(JSON.stringify(listaCampos));
            $('#modalActualizarCampoTipoFile').modal('hide');
        });

        $("#actualizarConfiguracionDetalladaCampoSelect").click(function() {
            $("#modalActualizarCampoTipoSelect").find("input, select").each(function(index, element) {
                if(element.id == 'select_field_multiple_selection'){
                    var valorInput = element.checked;
                }else{
                    var valorInput = $(this).val();
                }
                if(valorInput != ""){
                    conf[element.id] = valorInput;
                }
            });
            
            let campo = listaCampos.find(campo => campo.id === campo_id);
            campo.configuracion = conf;
            conf = {};
            $('#configuracion_campos').val(JSON.stringify(listaCampos));
            $('#modalActualizarCampoTipoSelect').modal('hide');
        });

        table = $('#configuracion_campos_table').DataTable( {
            scrollX: true,
            orderCellsTop: true,
            fixedHeader: true,
            destroy: true,
            paging: true,
            searching: true,
            autoWidth: true,
            responsive: false,
        });

        $('#select_field_tipo_catalogo').on('change', function () {
            let tipo_catalogo_id = $('#select_field_tipo_catalogo').val();
            let selected = '';

            $("#select_field_default_value").empty();
            $("#select_field_default_value").html('');
            $('#select_field_default_value').selectpicker('destroy');
            $('#select_field_default_value').addClass( "selectpicker" );

            if(tipo_catalogo_id != ""){
                
                $.ajax({
                    url: "{{url('/getCatalogoByTipoCatalogoId')}}",
                    type: "POST",
                    data: {
                        tipo_catalogo_id: tipo_catalogo_id,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        //$('#select_field_default_value').html('<option value="">Seleccione un valor por defecto:</option>');
                        $.each(response.catalogos, function (key, value) {
                            if(value.id == select_field_default_value){
                                selected = ' selected';
                            }
                            $("#select_field_default_value").append('<option value="' + value
                                .id + '">' + value.nombre + '</option>');
                        });

                        $("#select_field_default_value").selectpicker('val', select_field_default_value);
                        $('#select_field_default_value').selectpicker('render');
                        $('#select_field_default_value').selectpicker('refresh');
                        $('#modalActualizarCampoTipoSelect').modal('show');
                        
                    }
                });
            }
            $('#modalActualizarCampoTipoSelect').modal('show');
        });
    })

    let table = "";
    let tableRef = "";
    let conf = {};

    let configuraciones = '{{$secuenciaProceso->configuracion}}';
    configuraciones = configuraciones.replace(/&quot;/g, '"');
    configuraciones = JSON.parse(configuraciones);

    for (let prop in configuraciones) {
        console.log(`${prop}: ${configuraciones[prop]}`);
        setearConfiguracionObjeto(prop,configuraciones[prop]);
    }

    let listaCampos = '{{$listaCampos}}';
    listaCampos = listaCampos.replace(/&quot;/g, '"');
    listaCampos = JSON.parse(listaCampos);

    let objetoCorreo = '{{$configuracion_correo}}';
    objetoCorreo = objetoCorreo.replace(/&quot;/g, '"');
    objetoCorreo = JSON.parse(objetoCorreo);

    let contenido_html = '{{$contenido_html}}';
    contenido_html = contenido_html.replace(/&quot;/g, '"');
    contenido_html = contenido_html.replace(/&lt;/g, '<');
    contenido_html = contenido_html.replace(/&gt;/g, '>');

    for (let prop in objetoCorreo) {
        console.log(`${prop}: ${objetoCorreo[prop]}`);
        setearConfiguracionObjetoCorreo(prop,objetoCorreo[prop]);
    }

    for (let campo of listaCampos) {
        if(campo.configuracion === undefined){
            campo = inicializarConfiguracionCamposNuevos(campo);
        }
    }

    let campo_id = "";
    let select_field_default_value = "";
    let select_field_tipo_catalogo = "";

    function inicializarConfiguracionCamposNuevos(campo){
        
        campo.requerido = false;
        campo.editable = false;
        campo.visible = false;
        campo.configuracion = {};
        switch (campo.tipo_campo) {
            case "text":
                campo.configuracion = {
                    text_field_id: campo.id,
                    text_field_label: campo.nombre,
                    text_field_value: "",
                    text_field_helper_text: "",
                    text_field_placeholder: "",
                    text_field_class: "form-control",
                    text_field_name: campo.variable,
                    text_field_min_legth: "",
                    text_field_max_legth: ""
                };
            break;
            case "date":
                campo.configuracion = {
                    date_field_id: campo.id,
                    date_field_label: campo.nombre,
                    date_field_value: "",
                    date_field_helper_text: "",
                    date_field_placeholder: "",
                    date_field_class: "form-control",
                    date_field_name: campo.variable,
                    date_field_min_legth: "",
                    date_field_max_legth: ""
                };
            break;
            case "number":
                campo.configuracion = {
                    number_field_id: campo.id,
                    number_field_label: campo.nombre,
                    number_field_value: "",
                    number_field_helper_text: "",
                    number_field_placeholder: "",
                    number_field_class: "form-control",
                    number_field_name: campo.variable,
                    number_field_min: "",
                    number_field_max: "",
                    number_field_step: "",
                };
            break;
            case "email":
                campo.configuracion = {
                    email_field_id: campo.id,
                    email_field_label: campo.nombre,
                    email_field_value: "",
                    email_field_helper_text: "",
                    email_field_placeholder: "",
                    email_field_class: "form-control",
                    email_field_name: campo.variable,
                    email_field_max_legth: ""
                };
            break;
            case "file":
                campo.configuracion = {
                    file_field_id: campo.id,
                    file_field_label: campo.nombre,
                    file_field_helper_text: "",
                    file_field_placeholder: "",
                    file_field_class: "form-control",
                    file_field_name: campo.variable,
                    file_field_multiple_file_upload: false
                };
            break;
            case "select":
                campo.configuracion = {
                    select_field_id: campo.id,
                    select_field_tipo_catalogo: "",
                    select_field_default_value: "",
                    select_field_label: campo.nombre,
                    select_field_helper_text: "",
                    select_field_placeholder: "",
                    select_field_class: "form-control",
                    select_field_name: campo.variable,
                    select_field_multiple_selection: false
                };
            break;
        }
        return campo;  
        
    }

    function getField(id){
        campo_id = id;
        let campo = listaCampos.find(campo => campo.id === id);
        switch (campo.tipo_campo) {
            case "text":
                $("#modalActualizarCampoTipoTexto").find("input").each(function(index, element) {
                    let campo = listaCampos.find(campo => campo.id === id);
                    $('#'+element.id).val(campo.configuracion[element.id]);
                });
                $('#modalActualizarCampoTipoTexto').modal('show');
                break;
            case "date":
                $("#modalActualizarCampoTipoFecha").find("input").each(function(index, element) {
                    let campo = listaCampos.find(campo => campo.id === id);
                    $('#'+element.id).val(campo.configuracion[element.id]);
                });
                $('#modalActualizarCampoTipoFecha').modal('show');
                break;
            case "number":
                $("#modalActualizarCampoTipoNumerico").find("input").each(function(index, element) {
                    let campo = listaCampos.find(campo => campo.id === id);
                    $('#'+element.id).val(campo.configuracion[element.id]);
                });
                $('#modalActualizarCampoTipoNumerico').modal('show');
                break;
            case "email":
                $("#modalActualizarCampoTipoEmail").find("input").each(function(index, element) {
                    let campo = listaCampos.find(campo => campo.id === id);
                    $('#'+element.id).val(campo.configuracion[element.id]);
                });
                $('#modalActualizarCampoTipoEmail').modal('show');
                break;
            case "file":
                $("#modalActualizarCampoTipoFile").find("input").each(function(index, element) {
                    let campo = listaCampos.find(campo => campo.id === id);
                    if(element.id == 'file_field_multiple_file_upload'){
                        $('#'+element.id).prop("checked", campo.configuracion[element.id]);
                    }else{
                        $('#'+element.id).val(campo.configuracion[element.id]);
                    }
                });
                $('#modalActualizarCampoTipoFile').modal('show');
                break;
            case "select":
                $("#modalActualizarCampoTipoSelect").find("input, select").each(function(index, element) {
                    let campo = listaCampos.find(campo => campo.id === id);
                    if(element.id == 'select_field_multiple_selection'){
                        $('#'+element.id).prop("checked", campo.configuracion[element.id]);
                    }else if(element.id == 'select_field_tipo_catalogo'){
                        $('#'+element.id).val(campo.configuracion[element.id]).trigger('change');
                        select_field_tipo_catalogo = campo.configuracion[element.id];
                    }else if(element.id == 'select_field_default_value'){
                        select_field_default_value = campo.configuracion[element.id];
                        $('#'+element.id).val(campo.configuracion[element.id]);//.trigger('change');
                    }else{
                        $('#'+element.id).val(campo.configuracion[element.id]);
                    }
                });
                
                break;
        }
    }

    function generarConfiguracionObjeto(campo,valor){
        configuraciones[campo] = valor;
        $('#configuracion').val(JSON.stringify(configuraciones));
    }

    function setearConfiguracionObjeto(campo,valor){
        $('#'+campo).val(valor);
    }

    function setearConfiguracionObjetoCorreo(campo,valor){
        if(campo == 'habilitar_envio'){
            $('#'+campo).prop('checked', valor);
        }else if(campo == 'contenido_html'){
            $('#'+campo).val(contenido_html);
            objetoCorreo[campo] = contenido_html;
        }else{
            $('#'+campo).val(valor);
        }
    }

    function generarConfiguracionCamposObjeto(id,obj){
        let campo = listaCampos.find(campo => campo.id === id);
        if(obj.id == id + '_requerido'){
            campo.requerido = obj.checked;
        }if(obj.id == id + '_editable'){
            campo.editable = obj.checked;
        }if(obj.id == id + '_visible'){
            campo.visible = obj.checked;
        }
        
        $('#configuracion_campos').val(JSON.stringify(listaCampos));
    }

    function generarConfiguracionObjetoCorreo(campo,valor){
        objetoCorreo[campo] = valor;
        $('#configuracion_correo').val(JSON.stringify(objetoCorreo));
    }
</script>
@endsection