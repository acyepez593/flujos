<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class Tramite extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'tramites';
    protected $guard_name = 'tramites';
  
    protected $fillable = [
        'proceso_id',
        'secuencia_proceso_id',
        'funcionario_actual_id',
        'datos',
        'estatus'
    ];

    protected $dates = ['deleted_at'];

}