<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class Pantalla extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'pantallas';
    protected $guard_name = 'pantallas';
  
    protected $fillable = [
        'nombre',
        'descripcion',
        'estatus'
    ];


    protected $dates = ['deleted_at'];

}