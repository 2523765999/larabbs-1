<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;

class UsersController extends Controller
{
    //
    public function store(UserRequest $request)
    {
//        $verifyData = \Cache::get($request->verification_key);
//        $verifyData = \Cache::get($request->vertification_key);//verifaction_key
        $verifyData = \Cache::get($request->verification_key);//verifaction_key
//        dd($verifyData);
        if (!$verifyData) {
            return $this->response()->error('验证码失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {//veritification_code 如何
            return $this->response()->errorUnauthorized('验证码不正确');
        }
        $user = User::create([ //create 不是 created
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password)
        ]);

        \Cache::forget($request->verificaition_key);
        return $this->response->created();
    }
}
