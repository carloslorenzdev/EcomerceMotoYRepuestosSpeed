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
        'hidden_images',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'image_url' => 'array', // automatically serialize/deserialize JSON lists of images
        'hidden_images' => 'array',
        'stock' => 'integer',
    ];

    /**
     * Accessor to guarantee valid and secure image URLs.
     */
    protected function imageUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                if (!$value) return [];
                
                $images = is_string($value) ? json_decode($value, true) : $value;
                if (!is_array($images)) return [];
                
                return array_map(function ($url) {
                    // Si es una ruta relativa (ej: /products/foto.jpg), le agregamos el dominio completo
                    if (str_starts_with($url, '/')) {
                        return asset($url); 
                    }
                    // Si la imagen viene por HTTP inseguro, la forzamos a HTTPS para evitar bloqueos en celulares
                    if (str_starts_with($url, 'http://') && env('APP_ENV') === 'production') {
                        return str_replace('http://', 'https://', $url);
                    }
                    return $url;
                }, $images);
            }
        );
    }

    /**
     * Get only the visible images (not hidden).
     */
    public function getVisibleImagesAttribute(): array
    {
        $images = $this->image_url ?? [];
        $hidden = $this->hidden_images ?? [];
        
        return array_values(array_filter($images, function($url) use ($hidden) {
            return !in_array($url, $hidden);
        }));
    }

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
