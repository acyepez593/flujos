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
                'group_name' => 'flujo',
                'permissions' => [
                    // profile Permissions
                    'flujo.fallecimientos',
                    'flujo.funerarios',
                    'flujo.discapacidad',
                    
                ]
            ],
            [
                'group_name' => 'proceso',
                'permissions' => [
                    // proceso Permissions
                    'proceso.create',
                    'proceso.view',
                    'proceso.edit',
                    'proceso.delete',
                ]
            ],
            [
                'group_name' => 'tramite',
                'permissions' => [
                    // tramite Permissions
                    'tramite.create',
                    'tramite.view',
                    'tramite.edit',
                    'tramite.delete',
                    'tramite.reassign',
                ]
            ],
            [
                'group_name' => 'catalogo',
                'permissions' => [
                    // catalogo Permissions
                    'catalogo.create',
                    'catalogo.view',
                    'catalogo.edit',
                    'catalogo.delete',
                ]
            ],
            [
                'group_name' => 'pantalla',
                'permissions' => [
                    // pantalla Permissions
                    'pantalla.create',
                    'pantalla.view',
                    'pantalla.edit',
                    'pantalla.delete',
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

        // Permission List as array
        $permissionsToIniciadorFallecimientos = [

            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view'
                ]
            ],
            [
                'group_name' => 'flujo',
                'permissions' => [
                    // profile Permissions
                    'flujo.fallecimientos'
                ]
            ],
            [
                'group_name' => 'proceso',
                'permissions' => [
                    // proceso Permissions
                    'proceso.view'
                ]
            ],
            [
                'group_name' => 'tramite',
                'permissions' => [
                    // tramite Permissions
                    'tramite.create',
                    'tramite.view',
                    'tramite.edit'
                ]
            ],
        ];

        // Permission List as array
        $permissionsToIniciadorFunerarios = [

            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view'
                ]
            ],
            [
                'group_name' => 'flujo',
                'permissions' => [
                    // profile Permissions
                    'flujo.funerarios'
                ]
            ],
            [
                'group_name' => 'proceso',
                'permissions' => [
                    // proceso Permissions
                    'proceso.view'
                ]
            ],
            [
                'group_name' => 'tramite',
                'permissions' => [
                    // tramite Permissions
                    'tramite.create',
                    'tramite.view',
                    'tramite.edit'
                ]
            ],
        ];

        // Permission List as array
        $permissionsToIniciadorDiscapacidad = [

            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view'
                ]
            ],
            [
                'group_name' => 'flujo',
                'permissions' => [
                    // profile Permissions
                    'flujo.discapacidad'
                ]
            ],
            [
                'group_name' => 'proceso',
                'permissions' => [
                    // proceso Permissions
                    'proceso.view'
                ]
            ],
            [
                'group_name' => 'tramite',
                'permissions' => [
                    // tramite Permissions
                    'tramite.create',
                    'tramite.view',
                    'tramite.edit'
                ]
            ],
        ];

        // Permission List as array
        $permissionsToConsultorFlujos = [

            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view'
                ]
            ],
            [
                'group_name' => 'flujo',
                'permissions' => [
                    // profile Permissions
                    'flujo.fallecimientos',
                    'flujo.funerarios',
                    'flujo.discapacidad'
                ]
            ],
            [
                'group_name' => 'tramite',
                'permissions' => [
                    // tramite Permissions
                    'tramite.view',
                ]
            ],
        ];

        // Permission List as array
        $permissionsToGestorFlujo = [

            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view'
                ]
            ],
            [
                'group_name' => 'tramite',
                'permissions' => [
                    // tramite Permissions
                    'tramite.create',
                    'tramite.view',
                    'tramite.edit'
                ]
            ],
        ];

        // Permission List as array
        $permissionsToReasignarTramites = [

            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view'
                ]
            ],
            [
                'group_name' => 'tramite',
                'permissions' => [
                    // tramite Permissions
                    'tramite.view',
                    'tramite.reassign',
                ]
            ],
        ];

        // Create additional roles
        $roles = ['INICIADOR FALLECIMIENTOS','INICIADOR FUNERARIOS','INICIADOR DISCAPACIDAD','CONSULTOR FLUJOS','GESTOR FLUJO','REASIGNADOR TRAMITES'];
        foreach($roles as $rol){
            $roleSuperAdmin = Role::create(['name' => $rol, 'guard_name' => 'admin']);
            $permissions = [];
            switch ($rol) {
                case 'INICIADOR FALLECIMIENTOS':
                    $permissions = $permissionsToIniciadorFallecimientos;
                    break;
                case 'INICIADOR FUNERARIOS':
                    $permissions = $permissionsToIniciadorFunerarios;
                    break;
                case 'INICIADOR DISCAPACIDAD':
                    $permissions = $permissionsToIniciadorDiscapacidad;
                    break;
                case 'CONSULTOR FLUJOS':
                    $permissions = $permissionsToConsultorFlujos;
                    break;
                case 'GESTOR FLUJO':
                    $permissions = $permissionsToGestorFlujo;
                    break;
                case 'REASIGNADOR TRAMITES':
                    $permissions = $permissionsToReasignarTramites;
                    break;
            }

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
                    }else{
                        $permission = $permissionExist;
                        $roleSuperAdmin->givePermissionTo($permission);
                        $permission->assignRole($roleSuperAdmin);
                    }
                }
            }
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
