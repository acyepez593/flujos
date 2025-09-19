
@extends('backend.layouts.master')

@section('title')
Crear Campo por Sección - Admin Panel
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
                <h4 class="page-title pull-left">Crear Campo por Sección</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.seccionPantallas.index') }}">Todos los Campos por Secciones</a></li>
                    <li><span>Crear Campo por Sección</span></li>
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
                    <h4 class="header-title">Crear Nuevo Campo por Sección</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('admin.seccionPantallas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="seccion_pantalla_id">Seleccione una sección de pantalla:</label>
                                <select id="seccion_pantalla_id" name="seccion_pantalla_id" class="form-control selectpicker @error('seccion_pantalla_id') is-invalid @enderror" data-live-search="true" required>
                                    @foreach ($pantallas as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('seccion_pantalla_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Nombre</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                    @error('nombre')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tipo">Seleccione un Tipo:</label>
                                <select id="tipo" name="tipo" class="form-control selectpicker @error('tipo') is-invalid @enderror" data-live-search="true" required>
                                    <option value="text">TEXTO</option>
                                    <option value="textarea">ÁREA DE TEXTO</option>
                                    <option value="number">NUMÉRICO</option>
                                    <option value="email">EMAIL</option>
                                    <option value="date">FECHA</option>
                                    <option value="select">SELECCIÓN</option>
                                    <option value="custom">PERSONALIZADO</option>
                                </select>
                                @error('tipo')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="configuracion">Configuracion</label>
                                <div class="input-group mb-3">
                                <textarea id="configuracion" name="configuracion" class="form-control @error('configuracion') is-invalid @enderror" rows="5" cols="40" required></textarea>
                                    @error('configuracion')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.camposPorSecciones.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
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
    })
</script>
@endsection