<?php

namespace App\Enums;

enum Statuses
{
    case AWAITING_APPROVAL;
    case APPROVED;
    case DECLINED;
    case UNDER_DELIVERY;
    case FULFILLED;
    case CANCELED;
    case CREATED;

    public function getLabel(): string
    {
        return match ($this) {
            self::APPROVED => 'Approved',
            self::DECLINED => 'Declined',
            self::AWAITING_APPROVAL => 'Awaiting Approval',
            self::UNDER_DELIVERY => 'Under Delivery',
            self::FULFILLED => 'Fullfilled',
            self::CANCELED => 'Canceled',
            self::CREATED => 'Created',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::DECLINED => 'danger',
            self::AWAITING_APPROVAL => 'warning',
            self::UNDER_DELIVERY => 'info',
            self::FULFILLED => 'primary',
            self::CANCELED => 'dark',
            default => 'secondary',
        };
    }
}
