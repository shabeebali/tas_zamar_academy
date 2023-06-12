<?php

namespace App\Models;

use App\Enums\DonationPaymentStatus;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property DonationPaymentStatus $payment_status
 * @property string $payment_transaction_id
 * @property string $location
 * @property string $address
 * @property string $comment
 * @property string $designation
 * @property array $payment_meta
 * @mixin Eloquent
 */
class Donation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'payment_meta' => 'array',
        'payment_status' => DonationPaymentStatus::class,
        'created_at' => 'datetime: d M Y / H:i A',
        'amount' => 'float'
    ];
}
