<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryItem extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
