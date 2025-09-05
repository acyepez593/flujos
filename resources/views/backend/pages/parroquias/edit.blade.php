
@extends('backend.layouts.master')

@section('title')
Editar Parroquia - Panel Parroquia
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
                <h4 class="page-title pull-left">Editar Parroquia</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.parroquias.index') }}">Todas las Parroquias</a></li>
                    <li><span>Editar Parroquia - {{ $parroquia->nombre }}</span></li>
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
                    <h4 class="header-title">Editar Parroquia - {{ $parroquia->nombre }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.parroquias.update', $parroquia->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="provincia_id">Seleccione una Provincia:</label>
                                <select id="provincia_id" name="provincia_id" class="form-control selectpicker" data-live-search="true" required>
                                    <option value="">Seleccione una Provincia</option>
                                    @foreach ($provincias as $key => $value)
                                        <option value="{{ $key }}" {{$parroquia->provincia_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="canton_id">Seleccione un Cantón:</label>
                                <select id="canton_id" name="canton_id" class="form-control selectpicker" data-live-search="true" required>
                                    <option value="">Seleccione un Cantón</option>
                                    @foreach ($cantones as $key => $value)
                                        <option value="{{ $key }}" {{$parroquia->canton_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Nombre</label>
                                <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{ $parroquia->nombre }}" required autofocus>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.parroquias.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
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

        $('#provincia_id').on('change', function () {
            let provincia_id = this.value;
            $("#canton_id").html('');
            $.ajax({
                url: "{{url('/api/getCantonByProvincia')}}",
                type: "POST",
                data: {
                    provincia_id: provincia_id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#canton_id').html('<option value="">Seleccione un Cantón:</option>');
                    $.each(response.cantones, function (key, value) {
                        $("#canton_id").append('<option value="' + value
                            .id + '">' + value.nombre + '</option>');
                    });
                    $('#canton_id.selectpicker').selectpicker('refresh');
                }
            });
        });
    })
</script>
@endsection