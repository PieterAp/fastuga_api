<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthenticationController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->client = DB::table('oauth_clients')->where('id', 2)->first();
    }

    private function passportAuthenticationData($email, $password)
    {
        return [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $email,
            'password' => $password,
            'scope' => '*'
        ];
    }

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
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed. Wrong credentials.',
            ], 401);
        }

        $email = $request->input('email');
        $name = User::where('email',$email)->first()->name;

        // LOGIN SUCESSFULL
        $request = Request::create(
            '/oauth/token',
            'POST',
            $this->passportAuthenticationData($request->email, $request->password)
        );

        $response = app()->handle($request);

        $responseObject = json_decode((string) $response->content(), true);
        $responseObject['name'] = $name;
        $responseObject['email'] = $email;


        return $responseObject;
    }

    public function register(Request $request)
    {

        $data = array(
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
        );


        //return $data;
        //email validation is acepting bruno@gmail.com2 should it?
        //validate license plate format
        $validator = Validator::make(
            $data,
            array(
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required|same:password'
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

        $user = User::create($data);

        $dataCustomer = array(
            'user_id' => $user->id,    
            'points' => "0",    
            'phone' => $request->input('phone'),            
        );

        Customer::create($dataCustomer); 
        return response()->json(['success' => 'success'], 200);
    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
