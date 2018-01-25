<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ControllerTrait;
use App\User;
use Validator;

class UserController extends Controller
{
    use ControllerTrait;
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request)
    {
        return $this->sendData($request->user());
    }
}
