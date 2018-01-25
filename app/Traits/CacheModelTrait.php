<?php
namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\TaggableStore;

trait CacheModelTrait{
    

    public function save(array $options = [])
    {   //both inserts and updates
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags($this->table)->flush();
        }
        return parent::save($options);
    }
    public function delete(array $options = [])
    {   //soft or hard
        parent::delete($options);
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags($this->table)->flush();
        }
    }
    public function restore()
    {   //soft delete undo's
        parent::restore();
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags($this->table)->flush();
        }
    }
    
}

