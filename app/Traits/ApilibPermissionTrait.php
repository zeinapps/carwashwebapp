<?php
namespace App\Traits;

trait ApilibPermissionTrait{
    
    public function getRoles()
    {
        return $this->roles()->get();
    }
    
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'permission_role','permission_id', 'role_id');
    }
    
}
