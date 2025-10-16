
@extends('backend.layouts.master')

@section('title')
Crear Catálogo - Admin Panel
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
                <h4 class="page-title pull-left">Crear Catálogo</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.catalogos.index') }}">Todos los Catálogos</a></li>
                    <li><span>Crear Catálogo</span></li>
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
                    <h4 class="header-title">Crear Nuevo Catálogo</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('admin.catalogos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Nombre</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                    @error('nombre')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tipo_catalogo_id">Tipo Catálogo:</label>
                                <select id="tipo_catalogo_id" name="tipo_catalogo_id" class="form-control selectpicker" data-live-search="true">
                                    <option value="">Seleccione un Tipo Catálogo</option>
                                    @foreach ($tipoCatalogos as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_catalogo_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div id="catalogoDependiente" class="form-group col-md-6 col-sm-12">
                                <label for="catalogo_id">Catálogo:</label>
                                <select id="catalogo_id" name="catalogo_id" class="form-control selectpicker" data-live-search="true">
                                    
                                </select>
                                @error('catalogo_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="estatus">Seleccione un Estatus:</label>
                                <select id="estatus" name="estatus" class="form-control selectpicker @error('estatus') is-invalid @enderror" data-live-search="true" required>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
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
<!-- Start datatable js -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>

    let catalogosDependientesByTipoCatalogo = '{{$catalogosDependientesByTipoCatalogo}}';
    catalogosDependientesByTipoCatalogo = catalogosDependientesByTipoCatalogo.replace(/&quot;/g, '"');
    catalogosDependientesByTipoCatalogo = JSON.parse(catalogosDependientesByTipoCatalogo);

    let catalogosByTipoCatalogo = '{{$catalogosByTipoCatalogo}}'
    catalogosByTipoCatalogo = catalogosByTipoCatalogo.replace(/&quot;/g, '"');
    catalogosByTipoCatalogo = JSON.parse(catalogosByTipoCatalogo);

    $(document).ready(function() {
        $('.select2').select2();

        $("#tipo_catalogo_id").on("change", function() {
            if(catalogosDependientesByTipoCatalogo[$(this).val()] != undefined){
                $('#catalogo_id').selectpicker('destroy');
                $("#catalogo_id").html('');

                $.each(catalogosDependientesByTipoCatalogo[$(this).val()], function (key, value) {
                    if(key == 0){
                        $("#catalogo_id").append('<option value="' + value.id + '" selected>' + catalogosByTipoCatalogo[value.catalogo_id-1].nombre + ' - ' + value.nombre + '</option>');
                    }else{
                        $("#catalogo_id").append('<option value="' + value.id + '">' + catalogosByTipoCatalogo[value.catalogo_id-1].nombre + ' - ' + value.nombre + '</option>');
                    }
                    
                });
                $('#catalogo_id').selectpicker();
                $('.selectpicker').selectpicker('refresh');
                $('#catalogoDependiente').show();

            }else{
                $('#catalogo_id').selectpicker('destroy');
                $("#catalogo_id").html('');
                $('#catalogoDependiente').hide();
            }
            
            $('.selectpicker').selectpicker('refresh');

        });

        $('#catalogoDependiente').hide();
    })
</script>
@endsection