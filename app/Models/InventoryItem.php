<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $guarded = [
        'id'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
