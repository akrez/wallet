<?php

namespace App\Enums;

use App\Traits\Enum;

enum PaymentStatusEnum: string
{
    use Enum;

    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
