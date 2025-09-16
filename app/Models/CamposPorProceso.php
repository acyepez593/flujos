<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class CamposPorProceso extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'campos_por_procesos';
    protected $guard_name = 'campos_por_procesos';
  
    protected $fillable = [
        'nombre',
        'seccion_campo',
        'estatus'
    ];

    protected $dates = ['deleted_at'];

}