<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Tramite;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['dashboard.view']);

        $usuario_actual_id = Auth::id();
        $tramites_pendientes = Tramite::where('funcionario_actual_id',$usuario_actual_id)->where('estatus','<>', 'PAGADO')->get()->count();

        return view(
            'backend.pages.dashboard.index',
            [
                'total_admins' => Admin::count(),
                'total_roles' => Role::count(),
                'total_permisos' => Permission::count(),
                'total_tramites_pendientes' => $tramites_pendientes,
            ]
        );
    }
}
