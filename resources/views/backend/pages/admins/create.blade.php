
@extends('backend.layouts.master')

@section('title')
Admin Create - Admin Panel
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
                <h4 class="page-title pull-left">Crear Administrador</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.admins.index') }}">Todos los Administradores</a></li>
                    <li><span>Crear Administrador</span></li>
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
                    <h4 class="header-title">Crear Nuevo Usuario</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('admin.admins.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" onkeyup="this.value = this.value.toUpperCase();" placeholder="Ingresar el nombre" required autofocus value="{{ old('name') }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Ingresar el email" required value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Ingresar el password" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password_confirmation">Confirmar Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Password" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Asignar Roles</label>
                                <select name="roles[]" id="roles" class="form-control select2" multiple required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese el nombre de usuario" required value="{{ old('username') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="cargo_id">Seleccione el Cargo:</label>
                                <select id="cargo_id" name="cargo_id" class="form-control selectpicker" data-live-search="true" required>
                                    <option value="">Seleccione el Cargo</option>
                                    @foreach ($cargos as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('cargo_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="abreviacion_titulo_id">Seleccione la Abreviación del Título:</label>
                                <select id="abreviacion_titulo_id" name="abreviacion_titulo_id" class="form-control selectpicker" data-live-search="true" required>
                                    <option value="">Seleccione la Abreviación del Título</option>
                                    @foreach ($abreviacionesTitulo as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('abreviacion_titulo_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="agencia_id">Seleccione una Agencia:</label>
                                <select id="agencia_id" name="agencia_id" class="form-control selectpicker" data-live-search="true" required>
                                    <option value="">Seleccione una Agencia</option>
                                    @foreach ($agencias as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('agencia_id')
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
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->
        
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection