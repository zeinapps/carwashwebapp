<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ApilibPermissionTrait;
use App\Traits\CacheModelTrait;

class Permission extends Model {

    use ApilibPermissionTrait,CacheModelTrait;
    
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'name', 'display_name', 'description'
    ];

}
