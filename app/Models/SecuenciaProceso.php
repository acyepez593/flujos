<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class SecuenciaProceso extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'secuencia_procesos';
    protected $guard_name = 'secuencia_procesos';
  
    protected $fillable = [
        'nombre',
        'descripcion',
        'estatus',
        'tiempo_procesamiento',
        'actores',
        'configuracion',
        'configuracion_campos'
    ];


    protected $dates = ['deleted_at'];
}