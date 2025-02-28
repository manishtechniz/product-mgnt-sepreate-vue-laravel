<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];
    
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}