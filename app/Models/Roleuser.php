<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CacheModelTrait;

class Roleuser extends Model {
    use CacheModelTrait;
    
    protected $table = 'role_user';
    protected $fillable = [
        'user_id', 'role_id'
    ];
    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
