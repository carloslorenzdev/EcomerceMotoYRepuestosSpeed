<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'payment_gateway',
        'payment_id',
        'shipping_status',
        'shipping_tracking_number',
        'shipping_address',
        'customer_name',
        'customer_email',
        'customer_phone',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'shipping_address' => 'array', // street, city, commune, zip
    ];

    /**
     * User relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order items relationship.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
