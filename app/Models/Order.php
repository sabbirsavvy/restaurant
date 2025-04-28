<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_details',
        'total_amount',
        'status',
        'name',
        'email',
        'phone',
        'address1',
        'address2',
        'city',
        'county',
        'postcode',
        'type',
        'payment_method',
        'schedule',
    ];

    public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

}



