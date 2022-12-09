<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use App\Http\Resources\UserResource;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

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
        $data = $request->user();
        if ($data['type'] == "ED") {
            return new DriverResource($data);
        }
        return $data;
    }



    public function getActiveOrders(Request $request)
    {
        $user = $request->user();
        $data = Order::where('delivered_by', $user->id)->get();
        return UserResource::collection($data);
    }

    public function editProfile(Request $request)
    {
        $user = $request->user();
        $user->fill($request->all());
        if ($request->password != null) {
            $user['password'] = bcrypt($request->password);
        }
        $user->save();
        $driver = Driver::where('user_id', $user->id)->first();
        $driver->fill($request->all());
        $driver->save();
        return new DriverResource($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $user = new User();
        $user->fill($data);
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
        $user->fill($request->all());
        $user['password'] = bcrypt($request->password);
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
