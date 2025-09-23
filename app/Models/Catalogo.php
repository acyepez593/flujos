<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class Catalogo extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'catalogos';
    protected $guard_name = 'catalogos';
  
    protected $fillable = [
        'tipo_catalogo_id',
        'nombre',
        'estatus'
    ];

    protected $dates = ['deleted_at'];

}