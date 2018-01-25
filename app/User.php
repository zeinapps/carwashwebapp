<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;
use App\Traits\ApilibUserTrait;
use App\Traits\CacheModelTrait;

class User extends Authenticatable
{
    use Notifiable, ApilibUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'api_token', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token'
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->api_token = Uuid::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
    
    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class);
    }
}
