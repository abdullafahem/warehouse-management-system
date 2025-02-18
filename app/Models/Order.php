<?php

namespace App\Models;

use App\Enums\Statuses;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'submitted_date' => 'date',
        'deadline_date' => 'date',
        'status' => Statuses::class,
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function canUpdate()
    {
        return in_array($this->status, [Statuses::CREATED, Statuses::DECLINED]);
    }

    public function canCancel()
    {
        return !in_array($this->status, [Statuses::FULFILLED, Statuses::UNDER_DELIVERY, Statuses::CANCELED]);
    }

    public function canSubmit()
    {
        return in_array($this->status, [Statuses::CREATED, Statuses::DECLINED]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . date('Y') . '-' . str_pad((Order::count() + 1), 6, '0', STR_PAD_LEFT);
        });
    }
}
