<?php

namespace App\Enums;

enum Role: string
{
    case CLIENT = 'CLIENT';
    case WAREHOUSE_MANAGER = 'WAREHOUSE_MANAGER';
    case SYSTEM_ADMIN = 'SYSTEM_ADMIN';
}
