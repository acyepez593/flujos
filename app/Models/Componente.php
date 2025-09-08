<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class Componente extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'componentes';
    protected $guard_name = 'componentes';
  
    protected $fillable = [
        'nombre',
        'descripcion',
        'configuracion',
        'estatus',
    ];


    protected $dates = ['deleted_at'];

}