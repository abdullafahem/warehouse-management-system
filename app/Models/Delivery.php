<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'delivery_date' => 'date'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trucks()
    {
        return $this->belongsToMany(Truck::class, 'delivery_trucks');
    }
}
