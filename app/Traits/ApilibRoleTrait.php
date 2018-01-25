<?php
namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\TaggableStore;
use App\Lib\Lib;

trait ApilibRoleTrait{
    
    public function getPermissions()
    {
        return $this->permissions()->get();
    }
    
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'permission_role', 'role_id', 'permission_id');
    }
    
    public function groups()
    {
        return $this->belongsToMany(\App\Models\Group::class);
    }
    
    public function users()
    {
        return $this->belongsToMany(config('apilib.user_model'), 'role_user', 'role_id', 'user_id');
    }
    
    public function hasPermission($permissionname)
    {
        $key = __CLASS__.$this->{$this->primaryKey}.__FUNCTION__.$permissionname;
        if(Cache::getStore() instanceof TaggableStore) {
            return Cache::tags('permission_role')->remember($key, Lib::getExpiredCache(), function () use ($permissionname) {
                
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
    
    
    public function can($permission_name)
    {

        $key = __CLASS__ . $this->{$this->primaryKey} . __FUNCTION__ . $permission_name;
        $tag = ['group_role', 'permission_group'];
        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags($tag)->remember($key, Lib::getExpiredCache(), function () use ($permission_name) {
                $groups = $this->groups()->get();

                foreach ($groups as $group) {
                    if ($group->hasPermission($permission_name)) {
                        return true;
                    }
                }
                return false;
            });
        } else {
            $groups = $this->groups()->get();

            foreach ($groups as $group) {
                if ($group->hasPermission($permission_name)) {
                    return true;
                }
            }
            return false;
        }

    }
    
    public function attachPermission($permission)
    {
        if(is_object($permission)) {
            $permission = $permission->getKey();
        }

        if(is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->permissions()->attach($permission);
    }

    public function detachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->permissions()->detach($permission);
    }

    
    public function attachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    public function detachRoles($permissions=null)
    {
        if (!$permissions) $permissions = $this->permission()->get();
        foreach ($permissions as $permission) {
            $this->detachRole($permission);
        }
    }
    
    
}
