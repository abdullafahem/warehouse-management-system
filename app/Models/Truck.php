<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'container_volume' => 'decimal:2'
    ];

    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'delivery_trucks');
    }

    public function isAvailableOnDate($date)
    {
        return !$this->deliveries()
            ->whereDate('delivery_date', $date)
            ->exists();
    }
}
