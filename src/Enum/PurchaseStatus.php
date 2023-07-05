<?php

namespace App\Enum;

enum PurchaseStatus: int
{
    case ACCEPTED = 0;
    case ERROR = 1;
    case DECLINED = 2;
}
