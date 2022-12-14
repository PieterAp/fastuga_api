<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'description',
        'price',
        'photo_url',
    ];

    public function assignedOrders(){
        return $this->belongsToMany(Order::class, 'order_items');
    }

    public function assignedUsers(){
        return $this->belongsToMany(User::class, 'order_items');
    }

}
