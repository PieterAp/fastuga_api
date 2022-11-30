<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::where('id',$this->user_id)->first();
        //return $user;
        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone'=> $this->phone,
            'license_plate'=> $this->license_plate,
        ];      
    }
}
