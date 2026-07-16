<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'relbase_id',
        'sku',
        'name',
        'slug',
        'description',
        'price',
        'compare_at_price',
        'stock',
        'image_url',
        'category_id',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'image_url' => 'array', // automatically serialize/deserialize JSON lists of images
        'stock' => 'integer',
    ];

    /**
     * Category relationship.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Order items relationship.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
