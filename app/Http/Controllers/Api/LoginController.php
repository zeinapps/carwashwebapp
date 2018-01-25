<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ControllerTrait;
use Auth;
use Validator;
use App\User;

class LoginController extends Controller
{
    use ControllerTrait;
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError('username dan password harus sesuai');
        }
        Auth::attempt(['email' => $request->username, 'password' => $request->password]);
        $user = Auth::user()->makeVisible('api_token');
        return $this->sendData($user);
    }
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->toArray(),444);
        }
        
        $cek = User::where('email',$request->username)->first();
        if($cek){
            return $this->sendError('username sudah dipakai');
        }
        
        $new_user = User::create([
            'name' => $request->name,
            'email' => $request->username,
            'password' => bcrypt($request->password),
        ]);
        $new_user->attachRole(3); // 3 = pelanggan
        return $this->sendData($new_user);
    }
}
