<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

  
    public function login(Request $request)
    {
        //////////////////////////////////
        //       VALIDATE INPUTS       //

        $validator = Validator::make(
            array(
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ),
            array(
                'email' => 'required|email',
                'password' => 'required'
            )
        );

        if ($validator->fails()) {
            $fieldsWithErrorMessagesArray = $validator->messages()->get('*');
            return response()->json([
                'status' => 'error',
                'message' => $fieldsWithErrorMessagesArray,
            ], 400);
        }

        //////////////////////////////////////

        $remember = $request->has('remember') ? true : false;

        $correctCredentials = Auth::attempt(
            array(
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'blocked' => 0
            ),
            $remember
        );

        // WRONG CREDENTIALS
        if (!$correctCredentials) {
            return response()->json([
                'status' => 'error',
                'message' => 'Não foi possível iniciar sessão. Verifique se o seu email e password estão corretos.',
            ], 401);
        }

        // LOGIN SUCESSFULL
        $user = User::where('email',$request['email'])->first();
        $token = $user->createToken('myapptoken')->accessToken->token;

        $response = [
            'status'=>true,
            'message'=>'Login successful!',
            'data' =>[
                'user'=>$user,
                'token'=>$token
            ]
        ];

        return response($response,201);
    }

    public function logout(Request $request){
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });
    
        $response = [
            'status'=>true,
            'message'=>'Logout successfully',
        ];
        return response($response,201);
    }

}
