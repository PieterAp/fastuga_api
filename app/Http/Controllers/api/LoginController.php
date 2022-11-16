<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


const PASSPORT_SERVER_URL = "http://localhost/api";
const CLIENT_ID = 2;
const CLIENT_SECRET = 'Mhqxi4mCP8KJhZOjqPhvgXxNAonjVeTFixyLbdDR';

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
        
        $correctCredentials = auth()->attempt(
            array(
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'blocked' => 0
            ),
            $remember
        );

        // WRONG CREDENTIALS
        if (!$correctCredentials) {
            return response('Login failed. Wrong credentials', 401);
        }

        // LOGIN SUCESSFULL
        //maybe fix this red but not sure how
        //not sure if we need to verify if the user already have an token generated ou not
        $token = auth()->user()->createToken('API Token')->accessToken;
        return response(['token' => $token]);
    }
 
    public function register(Request $request)
    {

        $data = array(
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
            'license_plate' => $request->input('license_plate'),
        );

        //email validation is acepting bruno@gmail.com2 should it?
        //validate license plate format
        $validator = Validator::make(
            $data,
            array(
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required|same:password',
                'license_plate' => 'required'
            )
        );

        if ($validator->fails()) {
            $fieldsWithErrorMessagesArray = $validator->messages()->get('*');
            return response()->json([
                'status' => 'error',
                'message' => $fieldsWithErrorMessagesArray,
            ], 400);
        }

        $data['password'] = bcrypt($request->password);
        User::create($data);
       
        response()->json(['success' => 'success'], 200);
    }

    public function logout(Request $request){
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);    
    }

}
