<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'submitted_date' => 'date',
        'deadline_date' => 'date',
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
        return in_array($this->status, ['CREATED', 'DECLINED']);
    }

    public function canCancel()
    {
        return !in_array($this->status, ['FULFILLED', 'UNDER_DELIVERY', 'CANCELED']);
    }

    public function canSubmit()
    {
        return in_array($this->status, ['CREATED', 'DECLINED']);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . date('Y') . '-' . str_pad((Order::count() + 1), 6, '0', STR_PAD_LEFT);
        });
    }
}
