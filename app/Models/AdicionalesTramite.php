<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class AdicionalesTramite extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'adicionales_tramites';
    protected $guard_name = 'adicionales_tramites';
  
    protected $fillable = [
        'tramite_id',
        'proceso_id',
        'creado_por',
        'datos'
    ];

    protected $dates = ['deleted_at'];

}