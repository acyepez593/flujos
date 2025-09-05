<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class LogConfiguracionValidacion extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'log_configuracion_validacion';
    protected $guard_name = 'logConfiguracionValidacion';
  
    protected $fillable = [
        'validacion',
        'habilitar',

    ];

    protected $dates = ['deleted_at'];

}