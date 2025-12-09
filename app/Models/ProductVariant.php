<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'color',
        'size',
        'price',
        'original_price',
        'stock',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
    ];

    /**
     * Accessor untuk memastikan image URL selalu valid
     */
    public function getImageAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        // Jika sudah full URL, return langsung
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        // Jika path lokal, tambahkan /storage/ prefix
        return asset('storage/' . $value);
    }

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
