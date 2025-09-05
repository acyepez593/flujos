<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Class RolePermissionSeeder.
 *
 * @see https://spatie.be/docs/laravel-permission/v5/basic-usage/multiple-guards
 *
 * @package App\Database\Seeds
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * Enable these options if you need same role and other permission for User Model
         * Else, please follow the below steps for admin guard
         */

        // Create Roles and Permissions
        // $roleSuperAdmin = Role::create(['name' => 'superadmin']);
        // $roleAdmin = Role::create(['name' => 'admin']);
        // $roleEditor = Role::create(['name' => 'editor']);
        // $roleUser = Role::create(['name' => 'user']);


        // Permission List as array
        $permissions = [

            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view',
                    'dashboard.edit',
                ]
            ],
            [
                'group_name' => 'admin',
                'permissions' => [
                    // admin Permissions
                    'admin.create',
                    'admin.view',
                    'admin.edit',
                    'admin.delete',
                    
                ]
            ],
            [
                'group_name' => 'role',
                'permissions' => [
                    // role Permissions
                    'role.create',
                    'role.view',
                    'role.edit',
                    'role.delete',
                    
                ]
            ],
            [
                'group_name' => 'profile',
                'permissions' => [
                    // profile Permissions
                    'profile.view',
                    'profile.edit',
                    'profile.delete',
                    
                ]
            ],
            [
                'group_name' => 'lista',
                'permissions' => [
                    // lista Permissions
                    'lista.create',
                    'lista.view',
                    'lista.edit',
                    'lista.delete',
                    
                ]
            ],
            [
                'group_name' => 'tipoAtencion',
                'permissions' => [
                    // tipoAtencion Permissions
                    'tipoAtencion.create',
                    'tipoAtencion.view',
                    'tipoAtencion.edit',
                    'tipoAtencion.delete',
                    
                ]
            ],
            [
                'group_name' => 'tipoEstadoCaja',
                'permissions' => [
                    // tipoEstadoCaja Permissions
                    'tipoEstadoCaja.create',
                    'tipoEstadoCaja.view',
                    'tipoEstadoCaja.edit',
                    'tipoEstadoCaja.delete',
                    
                ]
            ],
            [
                'group_name' => 'tipo',
                'permissions' => [
                    // tipo Permissions
                    'tipo.create',
                    'tipo.view',
                    'tipo.edit',
                    'tipo.delete',
                    
                ]
            ],
            [
                'group_name' => 'institucion',
                'permissions' => [
                    // institucion Permissions
                    'institucion.create',
                    'institucion.view',
                    'institucion.edit',
                    'institucion.delete',
                    
                ]
            ],
            [
                'group_name' => 'tipoFirma',
                'permissions' => [
                    // tipoFirma Permissions
                    'tipoFirma.create',
                    'tipoFirma.view',
                    'tipoFirma.edit',
                    'tipoFirma.delete',
                    
                ]
            ],
            [
                'group_name' => 'tipoIngreso',
                'permissions' => [
                    // tipoIngreso Permissions
                    'tipoIngreso.create',
                    'tipoIngreso.view',
                    'tipoIngreso.edit',
                    'tipoIngreso.delete',
                    
                ]
            ],
            [
                'group_name' => 'tipoDocumento',
                'permissions' => [
                    // tipoDocumento Permissions
                    'tipoDocumento.create',
                    'tipoDocumento.view',
                    'tipoDocumento.edit',
                    'tipoDocumento.delete',
                    
                ]
            ],
            [
                'group_name' => 'tipoTramite',
                'permissions' => [
                    // tipoTramite Permissions
                    'tipoTramite.create',
                    'tipoTramite.view',
                    'tipoTramite.edit',
                    'tipoTramite.delete',
                    
                ]
            ],
            [
                'group_name' => 'estadoTramite',
                'permissions' => [
                    // estadoTramite Permissions
                    'estadoTramite.create',
                    'estadoTramite.view',
                    'estadoTramite.edit',
                    'estadoTramite.delete',
                    
                ]
            ],
            [
                'group_name' => 'provincia',
                'permissions' => [
                    // provincia Permissions
                    'provincia.create',
                    'provincia.view',
                    'provincia.edit',
                    'provincia.delete',
                    
                ]
            ],
            [
                'group_name' => 'canton',
                'permissions' => [
                    // canton Permissions
                    'canton.create',
                    'canton.view',
                    'canton.edit',
                    'canton.delete',
                    
                ]
            ],
            [
                'group_name' => 'parroquia',
                'permissions' => [
                    // parroquia Permissions
                    'parroquia.create',
                    'parroquia.view',
                    'parroquia.edit',
                    'parroquia.delete',
                    
                ]
            ],
            [
                'group_name' => 'prestadorSalud',
                'permissions' => [
                    // prestadorSalud Permissions
                    'prestadorSalud.create',
                    'prestadorSalud.view',
                    'prestadorSalud.edit',
                    'prestadorSalud.delete',
                    
                ]
            ],
            [
                'group_name' => 'bitacora',
                'permissions' => [
                    // bitacora Permissions
                    'bitacora.create',
                    'bitacora.view',
                    'bitacora.edit',
                    'bitacora.delete',
                    'bitacora.duplicar',
                    
                ]
            ],
            [
                'group_name' => 'oficio',
                'permissions' => [
                    // oficio Permissions
                    'oficio.create',
                    'oficio.view',
                    'oficio.edit',
                    'oficio.delete',
                    'oficio.duplicar',
                    'oficio.asignarNumeroCajaAuditoria',
                    'oficio.asignarFechaEnvioAuditoria',
                    'oficio.cerrarCaja',
                    'oficio.asignarPorNumeroCaja',
                    
                ]
            ],
            [
                'group_name' => 'rezagado',
                'permissions' => [
                    // rezagado Permissions
                    'rezagado.create',
                    'rezagado.view',
                    'rezagado.edit',
                    'rezagado.delete',
                    'rezagado.duplicar',
                    'rezagado.asignarNumeroCajaAuditoria',
                    'rezagado.asignarFechaEnvioAuditoria',
                    'rezagado.cerrarCaja',
                    'rezagado.asignarPorNumeroCaja',
                    
                ]
            ],
            [
                'group_name' => 'rezagadoLevantamientoObjecion',
                'permissions' => [
                    // rezagadoLevantamientoObjeciones Permissions
                    'rezagadoLevantamientoObjecion.create',
                    'rezagadoLevantamientoObjecion.view',
                    'rezagadoLevantamientoObjecion.edit',
                    'rezagadoLevantamientoObjecion.delete',
                    'rezagadoLevantamientoObjecion.duplicar',
                    'rezagadoLevantamientoObjecion.asignarNumeroCajaAuditoria',
                    'rezagadoLevantamientoObjecion.asignarFechaEnvioAuditoria',
                    'rezagadoLevantamientoObjecion.cerrarCaja',
                    'rezagadoLevantamientoObjecion.asignarPorNumeroCaja',
                    
                ]
            ],
            [
                'group_name' => 'extemporaneo',
                'permissions' => [
                    // extemporaneo Permissions
                    'extemporaneo.create',
                    'extemporaneo.view',
                    'extemporaneo.edit',
                    'extemporaneo.delete',
                    'extemporaneo.duplicar',
                    'extemporaneo.asignarNumeroCajaAuditoria',
                    'extemporaneo.asignarFechaEnvioAuditoria',
                    'extemporaneo.cerrarCaja',
                    'extemporaneo.asignarPorNumeroCaja',
                    
                ]
            ],
            [
                'group_name' => 'reporte',
                'permissions' => [
                    // reporte Permissions
                    'reporte.view',
                    'reporte.download',
                ]
            ],
            [
                'group_name' => 'reporteOficios',
                'permissions' => [
                    // reporteTramites Permissions
                    'reporteTramites.view',
                    'reporteTramites.download',
                ]
            ],
            [
                'group_name' => 'reporteBitacora',
                'permissions' => [
                    // reporteBitacora Permissions
                    'reporteBitacora.view',
                    'reporteBitacora.download',
                ]
            ],
            [
                'group_name' => 'configuracionValidacion',
                'permissions' => [
                    // configuracionValidacion Permissions
                    'configuracionValidacion.view',
                    'configuracionValidacion.edit',
                ]
            ],
            [
                'group_name' => 'logConfiguracionValidacion',
                'permissions' => [
                    // logConfiguracionValidacion Permissions
                    'logConfiguracionValidacion.view',
                ]
            ],
            [
                'group_name' => 'configuracionCamposReporte',
                'permissions' => [
                    // configuracionCamposReporte Permissions
                    'configuracionCamposReporte.create',
                    'configuracionCamposReporte.view',
                    'configuracionCamposReporte.edit',
                    'configuracionCamposReporte.delete',
                ]
            ],
            
        ];


        // Create and Assign Permissions
        // for ($i = 0; $i < count($permissions); $i++) {
        //     $permissionGroup = $permissions[$i]['group_name'];
        //     for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
        //         // Create Permission
        //         $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup]);
        //         $roleSuperAdmin->givePermissionTo($permission);
        //         $permission->assignRole($roleSuperAdmin);
        //     }
        // }

        // Do same for the admin guard for tutorial purposes.
        $admin = Admin::where('username', 'superadmin')->first();
        $roleSuperAdmin = $this->maybeCreateSuperAdminRole($admin);

        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                $permissionExist = Permission::where('name', $permissions[$i]['permissions'][$j])->first();
                if (is_null($permissionExist)) {
                    $permission = Permission::create(
                        [
                            'name' => $permissions[$i]['permissions'][$j],
                            'group_name' => $permissionGroup,
                            'guard_name' => 'admin'
                        ]
                    );
                    $roleSuperAdmin->givePermissionTo($permission);
                    $permission->assignRole($roleSuperAdmin);
                }
            }
        }

        // Assign super admin role permission to superadmin user
        if ($admin) {
            $admin->assignRole($roleSuperAdmin);
        }
    }

    private function maybeCreateSuperAdminRole($admin): Role
    {
        if (is_null($admin)) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'admin']);
        } else {
            $roleSuperAdmin = Role::where('name', 'superadmin')->where('guard_name', 'admin')->first();
        }

        if (is_null($roleSuperAdmin)) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'admin']);
        }

        return $roleSuperAdmin;
    }
}
