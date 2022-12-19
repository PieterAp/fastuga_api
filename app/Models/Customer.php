<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    //$fillable
    protected $fillable = [
        'points',
        'user_id',
        'default_payment_type',
        'default_payment_reference',
        'nif',
        'phone',
    ];

    public function orders(){
        return $this->hasMany(Order::class, 'customer_id');
    }


    public function users(){
        return $this->belongsTo(User::class , 'user_id');
    }

}
