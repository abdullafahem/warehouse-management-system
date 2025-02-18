<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    protected function containerVolume(): Attribute
    {
        return Attribute::make(
            get: fn(int $value) => $value / 1000000,
            set: fn(float $value) => $value * 1000000,
        );
    }
}
