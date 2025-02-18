<?php

namespace App\Enums;

enum Statuses: string
{
    case AWAITING_APPROVAL = 'Awaiting Approval';
    case APPROVED = 'Approved';
    case DECLINED = 'Declined';
    case UNDER_DELIVERY = 'Under Delivery';
    case FULFILLED = 'Fullfilled';
    case CANCELED = 'Canceled';
    case CREATED = 'Created';
}
