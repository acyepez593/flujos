<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class SeccionPantalla extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'seccion_pantallas';
    protected $guard_name = 'seccion_pantallas';
  
    protected $fillable = [
        'nombre',
        'descripcion',
        'estatus',
        'pantalla_id'
    ];


    protected $dates = ['deleted_at'];

}