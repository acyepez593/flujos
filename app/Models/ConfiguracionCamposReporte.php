<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class ConfiguracionCamposReporte extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'configuracion_campos_reporte';
    protected $guard_name = 'configuracionCamposReporte';
  
    protected $fillable = [
        'nombre',
        'habilitar',
        'campos',
        'responsable_id'

    ];

    protected $dates = ['deleted_at'];

}