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

                    @if ($usr->can('lista.view'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                            Catálogos
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.listas.create') || Route::is('admin.listas.index') || Route::is('admin.listas.edit') || Route::is('admin.listas.show') ? 'in' : '' }}">
                            @if ($usr->can('tipoAtencion.create') || $usr->can('tipoAtencion.view') ||  $usr->can('tipoAtencion.edit') ||  $usr->can('tipoAtencion.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Tipos Atención
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.tiposAtencion.create') || Route::is('admin.tiposAtencion.index') || Route::is('admin.tiposAtencion.edit') || Route::is('admin.tiposAtencion.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('tipoAtencion.view'))
                                        <li class="{{ Route::is('admin.tiposAtencion.index')  || Route::is('admin.tiposAtencion.edit') ? 'active' : '' }}"><a href="{{ route('admin.tiposAtencion.index') }}">Todos los Tipos de Atención</a></li>
                                    @endif

                                    @if ($usr->can('tipoAtencion.create'))
                                        <li class="{{ Route::is('admin.tiposAtencion.create')  ? 'active' : '' }}"><a href="{{ route('admin.tiposAtencion.create') }}">Crear Tipo Atención</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('tipoEstadoCaja.create') || $usr->can('tipoEstadoCaja.create') || $usr->can('tipoEstadoCaja.view') ||  $usr->can('tipoEstadoCaja.edit') ||  $usr->can('tipoEstadoCaja.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Tipos Estado Caja
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.tiposEstadoCaja.create') || Route::is('admin.tiposEstadoCaja.index') || Route::is('admin.tiposEstadoCaja.edit') || Route::is('admin.tiposEstadoCaja.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('tipoEstadoCaja.view'))
                                        <li class="{{ Route::is('admin.tiposEstadoCaja.index')  || Route::is('admin.tiposEstadoCaja.edit') ? 'active' : '' }}"><a href="{{ route('admin.tiposEstadoCaja.index') }}">Todos los Tipos de Estado Caja</a></li>
                                    @endif

                                    @if ($usr->can('tipoEstadoCaja.create'))
                                        <li class="{{ Route::is('admin.tiposEstadoCaja.create')  ? 'active' : '' }}"><a href="{{ route('admin.tiposEstadoCaja.create') }}">Crear Tipo Estado Caja</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('tipo.create') || $usr->can('tipo.view') ||  $usr->can('tipo.edit') ||  $usr->can('tipo.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Tipos
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.tipos.create') || Route::is('admin.tipos.index') || Route::is('admin.tipos.edit') || Route::is('admin.tipos.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('tipo.view'))
                                        <li class="{{ Route::is('admin.tipos.index')  || Route::is('admin.tipos.edit') ? 'active' : '' }}"><a href="{{ route('admin.tipos.index') }}">Todos los Tipos</a></li>
                                    @endif

                                    @if ($usr->can('tipo.create'))
                                        <li class="{{ Route::is('admin.tipos.create')  ? 'active' : '' }}"><a href="{{ route('admin.tipos.create') }}">Crear Tipo</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('institucion.create') || $usr->can('institucion.view') ||  $usr->can('institucion.edit') ||  $usr->can('institucion.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Instituciones
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.instituciones.create') || Route::is('admin.instituciones.index') || Route::is('admin.instituciones.edit') || Route::is('admin.instituciones.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('institucion.view'))
                                        <li class="{{ Route::is('admin.instituciones.index')  || Route::is('admin.instituciones.edit') ? 'active' : '' }}"><a href="{{ route('admin.instituciones.index') }}">Todas las Instituciones</a></li>
                                    @endif

                                    @if ($usr->can('institucion.create'))
                                        <li class="{{ Route::is('admin.instituciones.create')  ? 'active' : '' }}"><a href="{{ route('admin.instituciones.create') }}">Crear Institución</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('tipoFirma.create') || $usr->can('tipoFirma.view') ||  $usr->can('tipoFirma.edit') ||  $usr->can('tipoFirma.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Tipos Firma
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.tiposFirma.create') || Route::is('admin.tiposFirma.index') || Route::is('admin.tiposFirma.edit') || Route::is('admin.tiposFirma.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('tipoFirma.view'))
                                        <li class="{{ Route::is('admin.tiposFirma.index')  || Route::is('admin.tiposFirma.edit') ? 'active' : '' }}"><a href="{{ route('admin.tiposFirma.index') }}">Todos los Tipos de Firma</a></li>
                                    @endif

                                    @if ($usr->can('tipoFirma.create'))
                                        <li class="{{ Route::is('admin.tiposFirma.create')  ? 'active' : '' }}"><a href="{{ route('admin.tiposFirma.create') }}">Crear Tipo Firma</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('tipoIngreso.create') || $usr->can('tipoIngreso.view') ||  $usr->can('tipoIngreso.edit') ||  $usr->can('tipoIngreso.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Tipos Ingreso
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.tiposIngreso.create') || Route::is('admin.tiposIngreso.index') || Route::is('admin.tiposIngreso.edit') || Route::is('admin.tiposIngreso.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('tipoIngreso.view'))
                                        <li class="{{ Route::is('admin.tiposIngreso.index')  || Route::is('admin.tiposIngreso.edit') ? 'active' : '' }}"><a href="{{ route('admin.tiposIngreso.index') }}">Todos los Tipos de Ingreso</a></li>
                                    @endif

                                    @if ($usr->can('tipoIngreso.create'))
                                        <li class="{{ Route::is('admin.tiposIngreso.create')  ? 'active' : '' }}"><a href="{{ route('admin.tiposIngreso.create') }}">Crear Tipo Ingreso</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('tipoDocumento.create') || $usr->can('tipoDocumento.view') ||  $usr->can('tipoDocumento.edit') ||  $usr->can('tipoDocumento.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Tipos Documento
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.tiposDocumento.create') || Route::is('admin.tiposDocumento.index') || Route::is('admin.tiposDocumento.edit') || Route::is('admin.tiposDocumento.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('tipoDocumento.view'))
                                        <li class="{{ Route::is('admin.tiposDocumento.index')  || Route::is('admin.tiposDocumento.edit') ? 'active' : '' }}"><a href="{{ route('admin.tiposDocumento.index') }}">Todos los Tipos de Documento</a></li>
                                    @endif

                                    @if ($usr->can('tipoDocumento.create'))
                                        <li class="{{ Route::is('admin.tiposDocumento.create')  ? 'active' : '' }}"><a href="{{ route('admin.tiposDocumento.create') }}">Crear Tipo Documento</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('tipoTramite.create') || $usr->can('tipoTramite.view') ||  $usr->can('tipoTramite.edit') ||  $usr->can('tipoTramite.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Tipos Trámite
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.tiposTramite.create') || Route::is('admin.tiposTramite.index') || Route::is('admin.tiposTramite.edit') || Route::is('admin.tiposTramite.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('tipoTramite.view'))
                                        <li class="{{ Route::is('admin.tiposTramite.index')  || Route::is('admin.tiposTramite.edit') ? 'active' : '' }}"><a href="{{ route('admin.tiposTramite.index') }}">Todos los Tipos de Trámite</a></li>
                                    @endif

                                    @if ($usr->can('tipoTramite.create'))
                                        <li class="{{ Route::is('admin.tiposTramite.create')  ? 'active' : '' }}"><a href="{{ route('admin.tiposTramite.create') }}">Crear Tipo Trámite</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('estadoTramite.create') || $usr->can('estadoTramite.view') ||  $usr->can('estadoTramite.edit') ||  $usr->can('estadoTramite.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Estados Trámite
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.estadosTramite.create') || Route::is('admin.estadosTramite.index') || Route::is('admin.estadosTramite.edit') || Route::is('admin.estadosTramite.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('estadoTramite.view'))
                                        <li class="{{ Route::is('admin.estadsTramite.index')  || Route::is('admin.estadosTramite.edit') ? 'active' : '' }}"><a href="{{ route('admin.estadosTramite.index') }}">Todos los Tipos de Trámite</a></li>
                                    @endif

                                    @if ($usr->can('estadoTramite.create'))
                                        <li class="{{ Route::is('admin.estadosTramite.create')  ? 'active' : '' }}"><a href="{{ route('admin.estadosTramite.create') }}">Crear Estado Trámite</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('provincia.create') || $usr->can('provincia.view') ||  $usr->can('provincia.edit') ||  $usr->can('provincia.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Provincias
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.provincias.create') || Route::is('admin.provincias.index') || Route::is('admin.provincias.edit') || Route::is('admin.provincias.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('provincia.view'))
                                        <li class="{{ Route::is('admin.provincias.index')  || Route::is('admin.provincias.edit') ? 'active' : '' }}"><a href="{{ route('admin.provincias.index') }}">Todas las Provincias</a></li>
                                    @endif

                                    @if ($usr->can('provincia.create'))
                                        <li class="{{ Route::is('admin.provincias.create')  ? 'active' : '' }}"><a href="{{ route('admin.provincias.create') }}">Crear Provincia</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('canton.create') || $usr->can('canton.view') ||  $usr->can('canton.edit') ||  $usr->can('canton.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Cantones
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.cantones.create') || Route::is('admin.cantones.index') || Route::is('admin.cantones.edit') || Route::is('admin.cantones.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('canton.view'))
                                        <li class="{{ Route::is('admin.cantones.index')  || Route::is('admin.cantones.edit') ? 'active' : '' }}"><a href="{{ route('admin.cantones.index') }}">Todos los Cantones</a></li>
                                    @endif

                                    @if ($usr->can('canton.create'))
                                        <li class="{{ Route::is('admin.cantones.create')  ? 'active' : '' }}"><a href="{{ route('admin.cantones.create') }}">Crear Canton</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('parroquia.create') || $usr->can('parroquia.view') ||  $usr->can('parroquia.edit') ||  $usr->can('parroquia.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Parroquias
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.parroquias.create') || Route::is('admin.parroquias.index') || Route::is('admin.parroquias.edit') || Route::is('admin.parroquias.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('parroquia.view'))
                                        <li class="{{ Route::is('admin.parroquias.index')  || Route::is('admin.parroquias.edit') ? 'active' : '' }}"><a href="{{ route('admin.parroquias.index') }}">Todos los Parroquias</a></li>
                                    @endif

                                    @if ($usr->can('parroquia.create'))
                                        <li class="{{ Route::is('admin.parroquias.create')  ? 'active' : '' }}"><a href="{{ route('admin.parroquias.create') }}">Crear Parroquia</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('configuracionValidacion.view') ||  $usr->can('configuracionValidacion.edit'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-gear"></i><span>
                            Configuraciones
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.configuracionesValidacion.index') || Route::is('admin.configuracionesValidacion.edit') ? 'in' : '' }}">
                            
                            @if ($usr->can('configuracionValidacion.view'))
                                <li class="{{ Route::is('admin.configuracionesValidacion.index')  || Route::is('admin.configuracionesValidacion.edit') ? 'active' : '' }}"><a href="{{ route('admin.configuracionesValidacion.index') }}">Validaciones</a></li>
                            @endif
                            @if ($usr->can('logConfiguracionValidacion.view'))
                                <li class="{{ Route::is('admin.logsConfiguracionesValidacion.index')  || Route::is('admin.logsConfiguracionesValidacion.edit') ? 'active' : '' }}"><a href="{{ route('admin.logsConfiguracionesValidacion.index') }}">Logs Modicicaciones Validaciones</a></li>
                            @endif
                            @if ($usr->can('configuracionCamposReporte.view'))
                                <li class="{{ Route::is('admin.configuracionesCamposReporte.index')  || Route::is('admin.configuracionesCamposReporte.edit') ? 'active' : '' }}"><a href="{{ route('admin.configuracionesCamposReporte.index') }}">Campos Reporte</a></li>
                            @endif

                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('prestadorSalud.create') || $usr->can('prestadorSalud.view') ||  $usr->can('prestadorSalud.edit') ||  $usr->can('prestadorSalud.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-university"></i><span>
                            Prestadores Salud
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.prestadoresSalud.create') || Route::is('admin.prestadoresSalud.index') || Route::is('admin.prestadoresSalud.edit') || Route::is('admin.prestadoresSalud.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('prestadorSalud.view'))
                                <li class="{{ Route::is('admin.prestadoresSalud.index')  || Route::is('admin.prestadoresSalud.edit') ? 'active' : '' }}"><a href="{{ route('admin.prestadoresSalud.index') }}">Todos los Prestadores Salud</a></li>
                            @endif

                            @if ($usr->can('prestadorSalud.create'))
                                <li class="{{ Route::is('admin.prestadoresSalud.create')  ? 'active' : '' }}"><a href="{{ route('admin.prestadoresSalud.create') }}">Crear Prestador Salud</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('bitacora.create') || $usr->can('bitacora.view') ||  $usr->can('bitacora.edit') ||  $usr->can('bitacora.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-file-text"></i><span>
                            Registro de Bitácora
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.registrosBitacora.create') || Route::is('admin.registrosBitacora.index') || Route::is('admin.registrosBitacora.edit') || Route::is('admin.registrosBitacora.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('bitacora.view'))
                                <li class="{{ Route::is('admin.registrosBitacora.index')  || Route::is('admin.registrosBitacora.edit') ? 'active' : '' }}"><a href="{{ route('admin.registrosBitacora.index') }}">Todos los Registros de Bitácora</a></li>
                            @endif

                            @if ($usr->can('bitacora.create'))
                                <li class="{{ Route::is('admin.registrosBitacora.create')  ? 'active' : '' }}"><a href="{{ route('admin.registrosBitacora.create') }}">Crear Registro Bitácora</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('oficio.create') || $usr->can('oficio.view') ||  $usr->can('oficio.edit') ||  $usr->can('oficio.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-file-text"></i><span>
                            Trámites Normales
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.oficios.create') || Route::is('admin.oficios.index') || Route::is('admin.oficios.edit') || Route::is('admin.oficios.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('oficio.view'))
                                <li class="{{ Route::is('admin.oficios.index')  || Route::is('admin.oficios.edit') ? 'active' : '' }}"><a href="{{ route('admin.oficios.index') }}">Todos los Trámites</a></li>
                            @endif

                            @if ($usr->can('oficio.create'))
                                <li class="{{ Route::is('admin.oficios.create')  ? 'active' : '' }}"><a href="{{ route('admin.oficios.create') }}">Crear Trámite</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('rezagado.create') || $usr->can('rezagado.view') ||  $usr->can('rezagado.edit') ||  $usr->can('rezagado.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-file-text"></i><span>
                            Trámites Rezagados
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.rezagados.create') || Route::is('admin.rezagados.index') || Route::is('admin.rezagados.edit') || Route::is('admin.rezagados.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('rezagado.view'))
                                <li class="{{ Route::is('admin.rezagados.index')  || Route::is('admin.rezagados.edit') ? 'active' : '' }}"><a href="{{ route('admin.rezagados.index') }}">Todos los Trámites Rezagados</a></li>
                            @endif

                            @if ($usr->can('rezagado.create'))
                                <li class="{{ Route::is('admin.rezagados.create')  ? 'active' : '' }}"><a href="{{ route('admin.rezagados.create') }}">Crear Trámite Rezagado</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('rezagadoLevantamientoObjecion.create') || $usr->can('rezagadoLevantamientoObjecion.view') ||  $usr->can('rezagadoLevantamientoObjecion.edit') ||  $usr->can('rezagadoLevantamientoObjecion.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-file-text"></i><span>
                            Trámites Rezagados Levantamiento Objeciones
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.rezagadosLevantamientoObjeciones.create') || Route::is('admin.rezagadosLevantamientoObjeciones.index') || Route::is('admin.rezagadosLevantamientoObjeciones.edit') || Route::is('admin.rezagadosLevantamientoObjeciones.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('rezagadoLevantamientoObjecion.view'))
                                <li class="{{ Route::is('admin.rezagadosLevantamientoObjeciones.index')  || Route::is('admin.rezagadosLevantamientoObjeciones.edit') ? 'active' : '' }}"><a href="{{ route('admin.rezagadosLevantamientoObjeciones.index') }}">Todos los Trámite Rezagados Levantamiento Objeciones</a></li>
                            @endif

                            @if ($usr->can('rezagadoLevantamientoObjecion.create'))
                                <li class="{{ Route::is('admin.rezagadosLevantamientoObjeciones.create')  ? 'active' : '' }}"><a href="{{ route('admin.rezagadosLevantamientoObjeciones.create') }}">Crear Trámite Rezagado Levantamiento Objeción</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('extemporaneo.create') || $usr->can('extemporaneo.view') ||  $usr->can('extemporaneo.edit') ||  $usr->can('extemporaneo.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-file-text"></i><span>
                            Trámites Extemporaneos
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.extemporaneos.create') || Route::is('admin.extemporaneos.index') || Route::is('admin.extemporaneos.edit') || Route::is('admin.extemporaneos.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('extemporaneo.view'))
                                <li class="{{ Route::is('admin.extemporaneos.index')  || Route::is('admin.extemporaneos.edit') ? 'active' : '' }}"><a href="{{ route('admin.extemporaneos.index') }}">Todos los Trámites Extemporaneos</a></li>
                            @endif

                            @if ($usr->can('extemporaneo.create'))
                                <li class="{{ Route::is('admin.extemporaneos.create')  ? 'active' : '' }}"><a href="{{ route('admin.extemporaneos.create') }}">Crear Trámite Extemporaneo</a></li>
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