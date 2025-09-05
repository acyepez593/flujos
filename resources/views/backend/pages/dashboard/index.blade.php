
@extends('backend.layouts.master')

@section('title')
Dashboard Page - Admin Panel
@endsection


@section('admin-content')

@php
    $usr = Auth::guard('admin')->user();
@endphp
<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Dashboard</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="index.html">Home</a></li>
                    <li><span>Dashboard</span></li>
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
    <div class="col-lg-8">
        <div class="row">
            @if ($usr->can('role.create') || $usr->can('role.view') ||  $usr->can('role.edit') ||  $usr->can('role.delete'))
            <div class="col-md-6 mt-3 mb-3">
                <div class="card">
                    <div class="seo-fact sbg1">
                        <a href="{{ route('admin.roles.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-users"></i> Roles</div>
                                <h2>{{ $total_roles }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-6 mt-3 mb-3">
                <div class="card">
                    <div class="seo-fact sbg2">
                        <a href="#" onclick="return false;">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-check-square"></i> Permisos</div>
                                <h2>{{ $total_permisos }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @if ($usr->can('admin.create') || $usr->can('admin.view') ||  $usr->can('admin.edit') ||  $usr->can('admin.delete'))
            <div class="col-md-6 mt-md-3 mb-3">
                <div class="card">
                    <div class="seo-fact sbg3">
                        <a href="{{ route('admin.admins.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-user"></i> Usuarios</div>
                                <h2>{{ $total_admins }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @if ($usr->can('prestadorSalud.create') || $usr->can('prestadorSalud.view') ||  $usr->can('prestadorSalud.edit') ||  $usr->can('prestadorSalud.delete'))
            <div class="col-md-6 mt-md-3 mb-3">
                <div class="card">
                    <div class="seo-fact sbg4">
                        <a href="{{ route('admin.prestadoresSalud.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-university"></i> Prestadores de Salud</div>
                                <h2>{{ $total_prestadores_salud }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="row">
            @if ($usr->can('oficio.create') || $usr->can('oficio.view') ||  $usr->can('oficio.edit') ||  $usr->can('oficio.delete'))
            <div class="col-md-6 mt-md-3 mb-3">
                <div class="card">
                    <div class="seo-fact sbg1">
                        <a href="{{ route('admin.oficios.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-file-text"></i> Trámites Normales</div>
                                <h2>{{ $total_oficios }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @if ($usr->can('rezagado.create') || $usr->can('rezagado.view') ||  $usr->can('rezagado.edit') ||  $usr->can('rezagado.delete'))
            <div class="col-md-6 mt-md-3 mb-3">
                <div class="card">
                    <div class="seo-fact sbg5">
                        <a href="{{ route('admin.rezagados.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-file-text"></i> Trámites Rezagados</div>
                                <h2>{{ $total_rezagados }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="row">
            @if ($usr->can('rezagadoLevantamientoObjecion.create') || $usr->can('rezagadoLevantamientoObjecion.view') ||  $usr->can('rezagadoLevantamientoObjecion.edit') ||  $usr->can('rezagadoLevantamientoObjecion.delete'))
            <div class="col-md-6 mt-md-3 mb-3">
                <div class="card">
                    <div class="seo-fact sbg6">
                        <a href="{{ route('admin.rezagadosLevantamientoObjeciones.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-file-text"></i> Trámites Rezagados Levantamiento Objeciones</div>
                                <h2>{{ $total_rezagados_levantamiento_objeciones }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @if ($usr->can('extemporaneo.create') || $usr->can('extemporaneo.view') ||  $usr->can('extemporaneo.edit') ||  $usr->can('extemporaneo.delete'))
            <div class="col-md-6 mt-md-3 mb-3">
                <div class="card">
                    <div class="seo-fact sbg7">
                        <a href="{{ route('admin.extemporaneos.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-file-text"></i> Trámites Extemporaneos</div>
                                <h2>{{ $total_extemporaneos }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="row">
            @if ($usr->can('bitacora.create') || $usr->can('bitacora.view') ||  $usr->can('bitacora.edit') ||  $usr->can('bitacora.delete'))
            <div class="col-md-6 mt-md-3 mb-3">
                <div class="card">
                    <div class="seo-fact sbg1">
                        <a href="{{ route('admin.registrosBitacora.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-file-text"></i> Registros Bitácora</div>
                                <h2>{{ $total_registros_bitacora }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
  </div>
</div>
@endsection