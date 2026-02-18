
@extends('backend.layouts.master')

@section('title')
Editar Campos por Sección - Panel Campos por Sección
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Editar Campos por Proceso</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ url('admin') }}/camposPorProcesos/{{$proceso_id}}">Todos los Campos por Secciones</a></li>
                    <li><span>Editar Campos por Proceso - {{ $camposPorProceso->nombre }}</span></li>
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
                    <h4 class="header-title">Editar Campo por Proceso - {{ $camposPorProceso->nombre }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ url('admin') }}/camposPorProcesos/{{$proceso_id}}/{{$camposPorProceso->id}}/edit" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tipo_campo">Seleccione la Sección del campo:</label>
                                <select id="tipo_campo" name="tipo_campo" class="form-control selectpicker @error('seccion_campo') is-invalid @enderror" data-live-search="true" required>
                                    <option value="text" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'text' ? 'selected' : '' }}>TEXTO</option>
                                    <option value="textarea" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'textarea' ? 'selected' : '' }}>ÁREA DE TEXTO</option>
                                    <option value="hidden" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'hidden' ? 'selected' : '' }}>OCULTO</option>
                                    <option value="email" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'email' ? 'selected' : '' }}>EMAIL</option>
                                    <option value="number" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'number' ? 'selected' : '' }}>NUMÉRICO</option>
                                    <option value="date" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'date' ? 'selected' : '' }}>FECHA</option>
                                    <option value="datetime" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'datetime' ? 'selected' : '' }}>FECHA Y HORA</option>
                                    <option value="file" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'file' ? 'selected' : '' }}>ARCHIVO</option>
                                    <option value="select" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'select' ? 'selected' : '' }}>SELECCIONABLE</option>
                                    <option value="checkbox" {{ old('tipo_campo', $camposPorProceso->tipo_campo) == 'checkbox' ? 'selected' : '' }}>CHECKBOX</option>
                                </select>
                                @error('tipo_campo')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Nombre</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $camposPorProceso->nombre) }}" required>
                                    @error('nombre')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="variable">Variable</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('variable') is-invalid @enderror" id="variable" name="variable" value="{{ old('variable', $camposPorProceso->variable) }}" required>
                                    @error('variable')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="seccion_campo">Seleccione la Sección del campo:</label>
                                <select id="seccion_campo" name="seccion_campo" class="form-control selectpicker @error('seccion_campo') is-invalid @enderror" data-live-search="true" required>
                                    <option value="RECEPCION" {{ old('seccion_campo', $camposPorProceso->seccion_campo) == 'RECEPCION' ? 'selected' : '' }}>RECEPCION</option>
                                    <option value="SINIESTRO" {{ old('seccion_campo', $camposPorProceso->seccion_campo) == 'SINIESTRO' ? 'selected' : '' }}>SINIESTRO</option>
                                    <option value="VICTIMA" {{ old('seccion_campo', $camposPorProceso->seccion_campo) == 'VICTIMA' ? 'selected' : '' }}>VICTIMA</option>
                                    <option value="VEHICULO" {{ old('seccion_campo', $camposPorProceso->seccion_campo) == 'VEHICULO' ? 'selected' : '' }}>VEHICULO</option>
                                    <option value="RECLAMANTE" {{ old('seccion_campo', $camposPorProceso->seccion_campo) == 'RECLAMANTE' ? 'selected' : '' }}>RECLAMANTE</option>
                                    <option value="BENEFICIARIOS" {{ old('seccion_campo', $camposPorProceso->seccion_campo) == 'BENEFICIARIOS' ? 'selected' : '' }}>BENEFICIARIOS</option>
                                    <option value="MEDICA" {{ old('seccion_campo', $camposPorProceso->seccion_campo) == 'MEDICA' ? 'selected' : '' }}>MEDICA</option>
                                    <option value="PROCEDENCIA" {{ old('seccion_campo', $camposPorProceso->seccion_campo) == 'PROCEDENCIA' ? 'selected' : '' }}>PROCEDENCIA</option>
                                    <option value="FINANCIERO" {{ old('seccion_campo', $camposPorProceso->seccion_campo) == 'FINANCIERO' ? 'selected' : '' }}>FINANCIERO</option>
                                </select>
                                @error('seccion_campo')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="estatus">Seleccione un Estatus:</label>
                                <select id="estatus" name="estatus" class="form-control selectpicker @error('estatus') is-invalid @enderror" data-live-search="true" required>
                                    <option value="ACTIVO" {{ old('estatus', $camposPorProceso->estatus) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                                    <option value="INACTIVO" {{ old('estatus', $camposPorProceso->estatus) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                                </select>
                                @error('estatus')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ url('admin') }}/camposPorProcesos/{{$proceso_id}}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
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
    })
</script>
@endsection