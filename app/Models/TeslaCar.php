<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeslaCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'year',
        'variant',
        'price',
        'range_miles',
        'top_speed_mph',
        'zero_to_sixty',
        'drivetrain',
        'image_url',
        'images',
        'display_order',
        'is_available',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'zero_to_sixty' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Get all images for this car (including legacy image_url if no images array)
     */
    public function getAllImages()
    {
        $images = $this->images ?? [];
        
        // If no images array but has image_url, include it
        if (empty($images) && $this->image_url) {
            $images[] = $this->image_url;
        }
        
        return $images;
    }

    /**
     * Get primary image (first image or image_url)
     * Returns a path that can be used with asset() helper
     */
    public function getPrimaryImage()
    {
        $images = $this->getAllImages();
        if (!empty($images)) {
            // If it's already a full URL (starts with http), return as-is
            if (str_starts_with($images[0], 'http')) {
                return $images[0];
            }
            // Otherwise return the path
            return $images[0];
        }
        
        // Fallback to image_url or default
        if ($this->image_url) {
            return $this->image_url;
        }
        
        return 'images/tesla1.jpg';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

