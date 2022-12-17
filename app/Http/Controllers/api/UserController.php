<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * class TaskController extends Controller
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        if($user->type=="C"){
           $points = Customer::where('user_id','=',$user->id)->first()->points;
           $user['points'] = $points ;
        }

        return new UserResource($user);
    }

    public function changePassword(Request $request, User $user)
    {
        $data = array(
            'current_password' => $request->input('current_password'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
        );

        $validator = Validator::make(
            $data,
            array(
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

        if (Hash::check($request->current_password, $user['password'])) {
            $user['password'] = bcrypt($request->password);
            $user->save();
            return new UserResource($user);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'The current password is incorrect',
        ], 400);
    }

    
    public function editProfile(Request $request)
    {
        $user = $request->user();
        $user->fill($request->all());
        if ($request->password != null) {
            $user['password'] = bcrypt($request->password);
        }
        $user->save();
    
        return new UserResource($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->photo) {
            $upload_path = public_path('storage/fotos');
            $generated_new_name = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move($upload_path, $generated_new_name);

            $data = $request->all();
            $user = new User();
            $user->fill($data);
            $user['photo_url'] = $generated_new_name;
        } else {
            $data = $request->all();
            $user = new User();
            $user->fill($data);
        }

        if ($request->password) {
            $user['password'] = bcrypt($request->password);
        }

        $user->save();
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($request->photo) {
            $upload_path = public_path('storage/fotos');
            $generated_new_name = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move($upload_path, $generated_new_name);
            $user->fill($request->all());
            $user['photo_url'] = $generated_new_name;
        } else {
            $user->fill($request->all());
        }
        if ($request->password) {
            $user['password'] = bcrypt($request->password);
        }

        $user->save();
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return new UserResource($user);
    }
}
