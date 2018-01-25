<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ApilibRoleTrait;
use App\Traits\CacheModelTrait;

class Role extends Model {

    use ApilibRoleTrait,CacheModelTrait;
    
    public $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'name', 'display_name', 'descriptions'
    ];

}
