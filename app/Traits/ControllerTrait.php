<?php

namespace App\Traits;


use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\TaggableStore;
use URL;
use App\Lib\Lib;
use Cas;
use App\Models\Config;
use Auth;

trait ControllerTrait{
    public function getCurrentUser(){   
        return $id_paspor = Cas::getCurrentUser();
    }
    
    public function sendData($data=[],$log=false,$message="success"){   
        return Lib::sendData($data, $message, $log);
    }
    
    public function sendError($message="error",$code=405){   
        return Lib::sendError($message,$code);
    }
    public function getExpiredCache(){
        return Lib::getExpiredCache();
    }
    
    public function clearCache($tagCache){
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags($tagCache)->flush();
        }
    }
    
    public static function findFromCache($id,$model,$tagCache){
        $table_model = $model->getTable();
        $key = $table_model.$id;
        $expired = Lib::getExpiredCache();
        if(Cache::getStore() instanceof TaggableStore) {
            return Cache::tags($tagCache)->remember($key, $expired, function () use ($model,$id) {
                return $model->find($id);
            });
        }else{
            return $model->find($id);
        }
    }
    
    public static function paginateFromCache($tagCache,$model,$key){
        $expired = Lib::getExpiredCache();
        if(Cache::getStore() instanceof TaggableStore) {
            $result = Cache::tags($tagCache)->remember(URL::full().'_paginate_'.$key, $expired, function () use ($model) {
                return $model->paginate(config('apilib.paginate'));
            });
        }else{   
            $result = $model->paginate(config('apilib.paginate'));
        }
        return $result;
    }
    
    public static function getFromCache($tagCache,$model,$key){
        $expired = Lib::getExpiredCache();
        if(Cache::getStore() instanceof TaggableStore) {
            $result = Cache::tags($tagCache)->remember('get_'.$key, $expired, function () use ($model) {
                return $model->get();
            });
        }else{   
            $result = $model->get();
        }
        return $result;
    }
    
    public static function firstFromCache($tagCache,$model,$key){
        $expired = Lib::getExpiredCache();
        if(Cache::getStore() instanceof TaggableStore) {
            $result = Cache::tags($tagCache)->remember('first_'.$key, $expired, function () use ($model) {
                return $model->first();
            });
        }else{   
            $result = $model->first();
        }
        return $result;
    }
    
    public function userCanAddRole(){
        $user = Auth::user();
        $roleallow = "";
        if($user->hasRole('opr-instansi')){
            $roleallow = (new Config)->getConfig('opr-instansi_allow');
        }
        if($user->hasRole('admin-instansi')){
            $roleallow = (new Config)->getConfig('admin-instansi_allow');
        }
        if($user->is_admin()){
            $roleallow = (new Config)->getConfig('administrator_allow');
        }
        return $roleallow;
    }
    
    public function roleToEnroledMoodle(){
        return explode(',',(new Config)->getConfig('roleToEnroledMoodle'));
    }
    }
