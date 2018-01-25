<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\TaggableStore;
use App\Lib\Lib;

trait ApilibUserTrait
{

    public function getRoles()
    {
        return $this->roles()->get();
    }

    public function hasRole($role_name)
    {   
        $key = __CLASS__ . $this->{$this->primaryKey} . __FUNCTION__ . $role_name ;
        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags('role_user')->remember($key, Lib::getExpiredCache(), function ()
            use ($role_name) {
                if ($this->roles()->where('name', $role_name)->first()) {
                    return true;
                } else {
                    return false;
                }
            });
        } else {
            if ($this->roles()->where('name', $role_name)->first()) {
                    return true;
                } else {
                    return false;
                }
        }

    }

    public function canDo($permission_name)
    {
        if($this->hasRole('administrator')){
            return true;
        }
        $key = __CLASS__ . $this->{$this->primaryKey} . __FUNCTION__ . $permission_name;
        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags('users')->remember($key, Lib::getExpiredCache(), function () use ($permission_name) {
                foreach ($this->roles as $role) {
                    if ($role->hasPermission($permission_name)) {
                        return true;
                    }
                     //cek dari group
                    if ($role->can($permission_name)) {
                        return true;
                    }
                }
                return false;
            });
        } else {
            foreach ($this->roles as $role) {
                if ($role->hasPermission($permission_name)) {
                    return true;
                }
            }
            return false;
        }

    }
    
    public function attachRole($role)
    {
        
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
    }

    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->detach($role);
    }

    public function attachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    public function detachRoles($roles = null)
    {
        if (!$roles) $roles = $this->roles()->get();
        foreach ($roles as $role) {
            $this->detachRole($role);
        }
    }
}
