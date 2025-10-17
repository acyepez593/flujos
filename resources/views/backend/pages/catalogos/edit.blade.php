
@extends('backend.layouts.master')

@section('title')
Editar Catálogo - Panel Catálogo
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
                <h4 class="page-title pull-left">Editar Catálogo</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.catalogos.index') }}">Todos los Catálogos</a></li>
                    <li><span>Editar Catálogo - {{ $catalogo->nombre }}</span></li>
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
                    <h4 class="header-title">Editar Catalogo - {{ $catalogo->nombre }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.catalogos.update', $catalogo->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Nombre</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $catalogo->nombre) }}" required>
                                    @error('nombre')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tipo_catalogo_id">Seleccione un Tipo de Catálogo:</label>
                                <select id="tipo_catalogo_id" name="tipo_catalogo_id" class="form-control selectpicker @error('tipo_catalogo_id') is-invalid @enderror" data-live-search="true" required>
                                    <option value="">Seleccione una Tipo de Catálogo</option>
                                    @foreach ($tipoCatalogos as $key => $value)
                                        <option value="{{ $value->id }}" {{ old('tipo_catalogo_id', $value->id) == $catalogo->tipo_catalogo_id ? 'selected' : '' }}>{{ $value->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_catalogo_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div id="catalogoRelacionado" class="form-group col-md-6 col-sm-12">
                                <label for="catalogo_id">Seleccione el Catálogo Relacionado:</label>
                                <select id="catalogo_id" name="catalogo_id" class="form-control selectpicker @error('catalogo_id') is-invalid @enderror" data-live-search="true">
                                </select>
                                @error('catalogo_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="estatus">Seleccione un Estatus:</label>
                                <select id="estatus" name="estatus" class="form-control selectpicker @error('estatus') is-invalid @enderror" data-live-search="true" required>
                                    <option value="ACTIVO" {{ old('estatus', $catalogo->estatus) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                                    <option value="INACTIVO" {{ old('estatus', $catalogo->estatus) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                                </select>
                                @error('estatus')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.catalogos.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
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

    let catalogosRelacionadosByTipoCatalogo = '{{$catalogosRelacionadosByTipoCatalogo}}';
    catalogosRelacionadosByTipoCatalogo = catalogosRelacionadosByTipoCatalogo.replace(/&quot;/g, '"');
    catalogosRelacionadosByTipoCatalogo = JSON.parse(catalogosRelacionadosByTipoCatalogo);

    let catalogosByTipoCatalogo = '{{$catalogosByTipoCatalogo}}';
    catalogosByTipoCatalogo = catalogosByTipoCatalogo.replace(/&quot;/g, '"');
    catalogosByTipoCatalogo = JSON.parse(catalogosByTipoCatalogo);

    let tipoCatalogos = '{{$tipoCatalogos}}';
    tipoCatalogos = tipoCatalogos.replace(/&quot;/g, '"');
    tipoCatalogos = JSON.parse(tipoCatalogos);

    let catalogo_id = '{{$catalogo->catalogo_id}}';

    $(document).ready(function() {
        $('.select2').select2();

        $("#tipo_catalogo_id").on("change", function() {
            
            let temp = tipoCatalogos.find(tipo => tipo.id == $(this).val());
            if(temp.tipo_catalogo_relacionado_id != undefined){
                $('#catalogo_id').selectpicker('destroy');
                $("#catalogo_id").html('');
                $("#catalogo_id").append('<option value="">Seleccione el Catálogo Relacionado</option>');
                $.each(catalogosRelacionadosByTipoCatalogo[temp.tipo_catalogo_relacionado_id], function (key, value) {
                    if(value.id == catalogo_id){
                        $("#catalogo_id").append('<option value="' + value.id + '" selected>' + value.nombre + '</option>');
                    }else{
                        $("#catalogo_id").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    }
                    
                });
                $('#catalogo_id').selectpicker();
                $('.selectpicker').selectpicker('refresh');
                $('#catalogoRelacionado').show();

            }else{
                $('#catalogo_id').selectpicker('destroy');
                $("#catalogo_id").html('');
                $('#catalogoRelacionado').hide();
            }
            
            $('.selectpicker').selectpicker('refresh');

        });

        $("#tipo_catalogo_id").trigger("change");

    })
</script>
@endsection