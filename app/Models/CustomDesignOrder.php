<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomDesignOrder extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'product_name',
        'product_price',
        'cutting_type',
        'special_materials',
        'additional_description',
        'status',
        'total_price',
    ];

    protected $casts = [
        'special_materials' => 'array',
        'product_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the custom design order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with this order
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all uploaded files for this order
     */
    public function uploads(): HasMany
    {
        return $this->hasMany(CustomDesignUpload::class);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->total_price, 0, ',', '.');
    }
}
