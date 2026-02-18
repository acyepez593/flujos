
@extends('backend.layouts.master')

@section('title')
Editar Rango Discapacidad - Panel Rango Discapacidad
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
                <h4 class="page-title pull-left">Editar Rango Discapacidad</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.rangoDiscapacidades.index') }}">Todos los Rangos Discapacidad</a></li>
                    <li><span>Editar Rango Discapacidad - {{ $rangoDiscapacidad->grado_discapacidad }}</span></li>
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
                    <h4 class="header-title">Editar Rango Normativa - {{ $rangoDiscapacidad->grado_discapacidad }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.rangoDiscapacidades.update', $rangoDiscapacidad->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="normativa_id">Seleccione una Normativa:</label>
                                <select id="normativa_id" name="normativa_id" class="form-control selectpicker @error('normativa_id') is-invalid @enderror" data-live-search="true" required>
                                    <option value="">Seleccione una Normativa</option>
                                    @foreach ($normativas as $key => $value)
                                        <option value="{{ $value->id }}" {{ old('normativa_id', $value->id) == $rangoDiscapacidad->normativa_id ? 'selected' : '' }}>{{ $value->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('normativa_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="grado_discapacidad">Grado Discapacidad</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('grado_discapacidad') is-invalid @enderror" id="grado_discapacidad" name="grado_discapacidad" value="{{ old('grado_discapacidad', $rangoDiscapacidad->grado_discapacidad) }}" required>
                                    @error('grado_discapacidad')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="rango_desde">Rango Desde</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control @error('rango_desde') is-invalid @enderror" id="rango_desde" name="rango_desde" value="{{ old('rango_desde', $rangoDiscapacidad->rango_desde) }}" required>
                                    @error('rango_desde')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="rango_hasta">Rango Hasta</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control @error('rango_hasta') is-invalid @enderror" id="rango_hasta" name="rango_hasta" value="{{ old('rango_hasta', $rangoDiscapacidad->rango_hasta) }}" required>
                                    @error('rango_hasta')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="valor_cobertura">Valor Cobertura</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control @error('valor_cobertura') is-invalid @enderror" id="valor_cobertura" name="valor_cobertura" value="{{ old('valor_cobertura', $rangoDiscapacidad->valor_cobertura) }}" required>
                                    @error('valor_cobertura')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="estatus">Seleccione un Estatus:</label>
                                <select id="estatus" name="estatus" class="form-control selectpicker @error('estatus') is-invalid @enderror" data-live-search="true" required>
                                    <option value="ACTIVO" {{ old('estatus', $rangoDiscapacidad->estatus) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                                    <option value="INACTIVO" {{ old('estatus', $rangoDiscapacidad->estatus) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                                </select>
                                @error('estatus')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.rangoDiscapacidades.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
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