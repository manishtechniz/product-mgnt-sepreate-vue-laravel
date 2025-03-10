<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    /**
     * Summary of timestamps
     */
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'status'
    ];

    /**
     * Disclose custom attribute for json data.
     */
    protected $appends = ['image_url'];
    
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Create image url attribute.
     */
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }
}