<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function authenticate(Request $request)
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

        $token = Auth::attempt(
            array(
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'blocked' => 0
            ),
            $remember
        );

        // WRONG CREDENTIALS
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Não foi possível iniciar sessão. Verifique se o seu email e password estão corretos.',
            ], 401);
        }

        // LOGIN SUCESSFULL
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
