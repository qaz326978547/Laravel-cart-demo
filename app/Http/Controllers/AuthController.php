<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Carbon\Carbon;




class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $message = [
            'name.required' => '請輸入名稱',
            'email.required' => '請輸入email',
            'email.email' => 'email格式錯誤',
            'email.unique' => 'email已經被註冊',
            'password.required' => '請輸入密碼',
            'password.min' => '密碼最少6個字',
            'password.max' => '密碼最多12個字',
            'password.confirmed' => '密碼不一致',
        ];
        $form = $this->validate($request, [
            'name' => 'required',
            'email' => 'required | email | unique:users', // unique:users 是指users資料表的email欄位且不重複
            'password' => 'required | min:6 | max:12 | confirmed', // confirmed 是指password_confirmation欄位要和password欄位一樣
        ], $message);

        $user = User::create([
            'name' => $form['name'],
            'email' => $form['email'],
            'password' => bcrypt($form['password']),
        ]);
        
        return response()->json(['data'=> $user, 'message' => '註冊成功'], 201);
        
    }

    public function login(Request $request)
    {
        $message = [
            'email.required' => '請輸入email',
            'email.email' => 'email格式錯誤',
            'password.required' => '請輸入密碼',
        ];
        $form = $this->validate($request, [
            'email' => 'required | email',
            'password' => 'required',
        ], $message);

       if(!Auth::attempt($form)){
           return response()->json(['message' => '帳號或密碼有誤'], 401);

       }else{
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if($request->remember_me){
                $token->expires_at = now()->addWeeks(1); // 一周後過期
            }
            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
       }
    }


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => '登出成功'], 200);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    

}

