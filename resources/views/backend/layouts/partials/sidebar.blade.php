 <!-- sidebar menu area start -->
 @php
     $usr = Auth::guard('admin')->user();
 @endphp
 <div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}">
                <h2 class="text-white">Admin</h2> 
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">

                    @if ($usr->can('dashboard.view'))
                    <li class="active">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-dashboard"></i><span>dashboard</span></a>
                        <ul class="collapse">
                            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('role.create') || $usr->can('role.view') ||  $usr->can('role.edit') ||  $usr->can('role.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                            Roles & Permisos
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : '' }}">
                            @if ($usr->can('role.view'))
                                <li class="{{ Route::is('admin.roles.index')  || Route::is('admin.roles.edit') ? 'active' : '' }}"><a href="{{ route('admin.roles.index') }}">Todos los Roles</a></li>
                            @endif
                            @if ($usr->can('role.create'))
                                <li class="{{ Route::is('admin.roles.create')  ? 'active' : '' }}"><a href="{{ route('admin.roles.create') }}">Crear Role</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    
                    @if ($usr->can('admin.create') || $usr->can('admin.view') ||  $usr->can('admin.edit') ||  $usr->can('admin.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                            Admins
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('admin.view'))
                                <li class="{{ Route::is('admin.admins.index')  || Route::is('admin.admins.edit') ? 'active' : '' }}"><a href="{{ route('admin.admins.index') }}">Todos los Admins</a></li>
                            @endif

                            @if ($usr->can('admin.create'))
                                <li class="{{ Route::is('admin.admins.create')  ? 'active' : '' }}"><a href="{{ route('admin.admins.create') }}">Crear Admin</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('proceso.create') || $usr->can('proceso.view') ||  $usr->can('proceso.edit') ||  $usr->can('proceso.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-file-text"></i><span>
                            Procesos
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.procesos.create') || Route::is('admin.procesos.index') || Route::is('admin.procesos.edit') || Route::is('admin.procesos.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('proceso.view'))
                                <li class="{{ Route::is('admin.procesos.index')  || Route::is('admin.procesos.edit') ? 'active' : '' }}"><a href="{{ route('admin.procesos.index') }}">Todos los Procesos</a></li>
                            @endif

                            @if ($usr->can('proceso.create'))
                                <li class="{{ Route::is('admin.procesos.create')  ? 'active' : '' }}"><a href="{{ route('admin.procesos.create') }}">Crear Proceso</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if ($usr->can('pantalla.create') || $usr->can('pantalla.view') ||  $usr->can('pantalla.edit') ||  $usr->can('pantalla.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-file-text"></i><span>
                            Pantallas
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.pantallas.create') || Route::is('admin.pantallas.index') || Route::is('admin.pantallas.edit') || Route::is('admin.pantallas.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('pantalla.view'))
                                <li class="{{ Route::is('admin.pantallas.index')  || Route::is('admin.pantallas.edit') ? 'active' : '' }}"><a href="{{ route('admin.pantallas.index') }}">Todas los Pantallas</a></li>
                            @endif

                            @if ($usr->can('pantalla.create'))
                                <li class="{{ Route::is('admin.pantallas.create')  ? 'active' : '' }}"><a href="{{ route('admin.pantallas.create') }}">Crear Pantalla</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('reporte.view') || $usr->can('reporteTramites.view') || $usr->can('reporteBitacora.view'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-download"></i><span>
                            Reportes
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.oficios.index') || Route::is('admin.rezagados.index') || Route::is('admin.rezagadosLevantamientoObjeciones.index') || Route::is('admin.extemporaneos.index') ? 'in' : '' }}">
                            @if ($usr->can('reporte.view'))
                                <li class="{{ Route::is('admin.reportes.index')  ? 'active' : '' }}"><a href="{{ route('admin.reportes.index') }}">Reporte Etiquetado Caja</a></li>
                            @endif
                            @if ($usr->can('reporteTramites.view'))
                                <li class="{{ Route::is('admin.reportes.create')  ? 'active' : '' }}"><a href="{{ route('admin.reportes.create') }}">Generar Reporte</a></li>
                            @endif
                            @if ($usr->can('reporteBitacora.view'))
                                <li class="{{ Route::is('admin.reportes.bitacora')  ? 'active' : '' }}"><a href="{{ route('admin.reportes.bitacora') }}">Generar Reporte Bitácora</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->