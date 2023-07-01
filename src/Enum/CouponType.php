<?php

namespace App\Enum;

enum CouponType: int
{
    case FIXED = 0;
    case PERCENT = 1;
}