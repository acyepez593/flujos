<?php
  
namespace App\Models;
  
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
  
class File extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;
  
    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $table = 'files';
    protected $guard_name = 'files';
  
    protected $fillable = [
        'name','oficio_id'
    ];

    protected $dates = ['deleted_at'];

}