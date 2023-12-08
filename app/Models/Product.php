<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['title', 'slug', 'published', 'description', 'price', 'stock', 'category_id', 'created_by', 'updated_by'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function user_creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function user_updater(): BelongsTo
    {
        return $this->belongsTo(User::class, "updated_by");
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function product_images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
