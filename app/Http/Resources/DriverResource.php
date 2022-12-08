<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Driver;

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
       // return parent::toArray($request);      
        $driver = Driver::where('user_id',$this->id)->first();
        if($driver!=null){
            return  [
                'name' => $this->name,
                'email' => $this->email,
                'phone'=>$driver->phone,
                'license_plate'=> $driver->license_plate,
                'balance'=> $driver->balance
            ]; 
        }else{
            return parent::toArray($request);   
        }
            
    }
}
