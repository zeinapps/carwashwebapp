<?php

namespace App\Lib;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Route;
use URL;
use Request;
use Auth;

class Lib {
    public static function sendData($data=[],$message="success",$log = false){ 
        if($log){
            $mes = is_array($message) ? json_encode($message) : $message;
            $currentRoute = Route::getCurrentRoute();
            $attributes = $currentRoute->getAction();
            $description = isset($attributes['description']) ? $attributes['description'] : null;
            $name = $currentRoute->getName();
            $context = [
                'url' => URL::full(),
                'as' => $name,
                'deskripsi' => $description,
                'user' => Auth::user(),
                'params' => Request::all()
            ];
            Log::info($mes,$context);
        }
        
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => is_array($message) ? $message : [$message],
            'result' => $data,
            ]);
    }
    
    public static function sendError($message="error",$code=100){ 
        $mes = is_array($message) ? json_encode($message) : $message;
        $currentRoute = Route::getCurrentRoute();
        $attributes = $currentRoute->getAction();
        $description = isset($attributes['description']) ? $attributes['description'] : null;
        $name = $currentRoute->getName();
        $context = [
            'url' => URL::full(),
            'as' => $name,
            'deskripsi' => $description,
            'user' => Auth::user(),
            'params' => Request::all()
        ];
        if($code == 444){
            Log::warning($mes,$context);
        }else if($code == 100) {
            Log::error($mes,$context);
            $message = "Terjadi kendala pada media penyimpanan DB, coba kembali beberapa saat lagi";
        }else{
            Log::error($mes,$context);
        }
        
        return response()->json([
            'status' => false,
            'code' => $code,
            'message' => is_array($message) ? $message : [$message],
            'result' => null,
            ]);
    }
    public static function getExpiredCache(){
        $minute = 60;
        return Carbon::now()->addMinutes($minute);
    }
}