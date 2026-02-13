<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class NormativaDiscapacidad extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'normativa_discapacidades';
    protected $guard_name = 'normativa_discapacidades';
  
    protected $fillable = [
        'nombre',
        'estatus'
    ];

    protected $dates = ['deleted_at'];

}