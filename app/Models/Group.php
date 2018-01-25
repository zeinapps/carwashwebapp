<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 27 Sep 2017 13:47:10 +0700.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CacheModelTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\TaggableStore;
use App\Lib\Lib;

class Group extends Model
{
    use CacheModelTrait;
    
	protected $fillable = [
		'name',
		'display_name',
		'description'
	];

	public function roles()
	{
		return $this->belongsToMany(\App\Models\Role::class);
	}
        
        public function permissions()
	{
		return $this->belongsToMany(\App\Models\Permission::class,'permission_group');
	}
        
        public function hasPermission($permissionname)
        {
            $key = __CLASS__.$this->{$this->primaryKey}.__FUNCTION__.$permissionname;
            if(Cache::getStore() instanceof TaggableStore) {
                return Cache::tags('permission_group')->remember($key, Lib::getExpiredCache(), function () use ($permissionname) {
                    if($this->permissions()->where('name',$permissionname)->first()){
                        return true;
                    }else{
                        return false;
                    }
                });
            }else{
                if($this->permissions()->where('name',$permissionname)->first()){
                    return true;
                }else{
                    return false;
                }
            }
        }
        
}
