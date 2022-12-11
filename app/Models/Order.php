<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    protected $fillable = [
        //to be possible to associate
        //customer to an order
        'customer_id',
        'status',
        'delivery_address',
        'pickup_address',
        'delivery_distance',
        'delivery_time',
        'delivered_by',
        'created_at'
    ];

}
