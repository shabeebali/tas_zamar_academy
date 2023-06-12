<?php

namespace App\Enums;

enum DonationPaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
    case FAILED = 'failed';
}
