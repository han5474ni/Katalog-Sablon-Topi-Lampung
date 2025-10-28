<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'subcategory',
        'price',
        'original_price',
        'image',
        'images',
        'colors',
        'sizes',
        'stock',
        'views',
        'sales',
        'is_featured',
        'is_active',
        'custom_design_allowed',
    ];

    protected $casts = [
        'images' => 'array',
        'colors' => 'array',
        'sizes' => 'array',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'custom_design_allowed' => 'boolean',
    ];

    // Auto-generate slug from name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Scopes for filtering
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeReady($query)
    {
        return $query->where('is_active', true)
                     ->where('stock', '>', 0);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSubcategory($query, $subcategory)
    {
        return $query->where('subcategory', $subcategory);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByColors($query, array $colors)
    {
        return $query->where(function($q) use ($colors) {
            foreach ($colors as $color) {
                $q->orWhereJsonContains('colors', $color);
            }
        });
    }

    public function scopeFilterBySizes($query, array $sizes)
    {
        return $query->where(function($q) use ($sizes) {
            foreach ($sizes as $size) {
                $q->orWhereJsonContains('sizes', $size);
            }
        });
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeSortBy($query, $sort)
    {
        switch ($sort) {
            case 'newest':
                return $query->orderBy('created_at', 'desc');
            case 'price_low':
                return $query->orderBy('price', 'asc');
            case 'price_high':
                return $query->orderBy('price', 'desc');
            case 'popular':
                return $query->orderBy('views', 'desc');
            default: // most_popular
                return $query->orderBy('sales', 'desc');
        }
    }

    // Increment views
    public function incrementViews()
    {
        $this->increment('views');
    }

    // Check if product is in stock
    public function inStock()
    {
        return $this->stock > 0;
    }

    // Check if product is ready (active + in stock)
    public function isReady()
    {
        return $this->is_active && $this->stock > 0;
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.');
    }
}
