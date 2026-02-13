<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class RangoDiscapacidad extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'rango_discapacidades';
    protected $guard_name = 'rango_discapacidades';
  
    protected $fillable = [
        'nombre_normativa',
        'grado_discapacidad',
        'rango_desde',
        'rango_hasta',
        'valor_cobertura',
        'vigencia_desde'
    ];

    protected $dates = ['deleted_at'];

}