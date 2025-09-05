<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Oficio;
use App\Models\Rezagado;
use App\Models\RezagadoLevantamientoObjecion;
use App\Models\Extemporaneo;
use App\Models\PrestadorSalud;
use App\Models\RegistroBitacora;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['dashboard.view']);

        return view(
            'backend.pages.dashboard.index',
            [
                'total_admins' => Admin::count(),
                'total_roles' => Role::count(),
                'total_permisos' => Permission::count(),
                'total_prestadores_salud' => PrestadorSalud::count(),
                'total_registros_bitacora' => RegistroBitacora::count(),
                'total_oficios' => Oficio::count(),
                'total_rezagados' => Rezagado::count(),
                'total_rezagados_levantamiento_objeciones' => RezagadoLevantamientoObjecion::count(),
                'total_extemporaneos' => Extemporaneo::count(),
            ]
        );
    }
}
