<?php

namespace Takshak\Ashop\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Takshak\Imager\Facades\Placeholder;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the parentCategory that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function parent(): BelongsTo
    {
        return $this->parentCategory();
    }

    /**
     * Get all of the children for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function metas(): MorphMany
    {
        return $this->morphMany(ShopMeta::class, 'metable');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }
    public function variations()
    {
        return $this->belongsToMany(Variation::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }


    public function scopeActive(Builder $query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('featured', true);
    }
    public function scopeParent(Builder $query)
    {
        return $query->whereNull('category_id');
    }
    public function scopeChild(Builder $query)
    {
        return $query->whereNotNull('parent');
    }
    public function scopeInFront(Builder $query)
    {
        return $query->where('in_front', true);
    }

    public function image_lg()
    {
        return ($this->image_lg && Storage::disk('public')->exists($this->image_lg))
            ? storage($this->image_lg)
            : $this->placeholderImage();
    }
    public function image_md()
    {
        return ($this->image_md && Storage::disk('public')->exists($this->image_md))
            ? storage($this->image_md)
            : $this->placeholderImage();
    }
    public function image_sm()
    {
        return ($this->image_sm && Storage::disk('public')->exists($this->image_sm))
            ? storage($this->image_sm)
            : $this->placeholderImage();
    }
    public function placeholderImage()
    {
        return Placeholder::width(900)
            ->height(800)
            ->text($this->display_name)
            ->url();
    }
    public function image($size = null)
    {
        if ($size == 'sm') {
            return $this->image_sm();
        } elseif ($size == 'md') {
            return $this->image_md();
        } elseif ($size == 'lg') {
            return $this->image_lg();
        } else {
            return $this->image_md();
        }
    }
}