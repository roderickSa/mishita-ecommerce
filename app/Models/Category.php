<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;

class Category extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['name', 'slug', 'created_by', 'updated_by'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
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
}
