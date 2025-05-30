<?php

namespace Takshak\Ashop\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Facades\Agent;
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

    /**
     * The brands that belong to the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    public function metas(): MorphMany
    {
        return $this->morphMany(ShopMeta::class, 'metable');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
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
    public function scopeIsParent(Builder $query)
    {
        return $query->whereNull('category_id');
    }
    public function scopeChild(Builder $query)
    {
        return $query->whereNotNull('parent');
    }
    public function scopeIsTop(Builder $query)
    {
        return $query->where('is_top', true);
    }

    public function getMeta($key, $default = null)
    {
        if (!$this->relationLoaded('metas')) {
            $this->load('metas');
        }

        $meta = $this->metas->where('key', $key)->first();

        if (!$meta && is_null($default)) {
            return null;
        }

        if (!$meta && !is_null($default)) {
            return $default;
        }

        $value = ($meta->is_file && $meta->value) ? storage($meta->value) : $meta->value;

        return $value ?? $default;
    }

    public function getTaxes($default = true)
    {
        if (!$this->relationLoaded('metas')) {
            $this->load('metas');
        }

        $taxes = $this->metas->where('key', 'taxes')->mapWithKeys(function ($tax, $key) {
            return [$tax->name => $tax->value];
        });

        if(count($taxes)){
            return $taxes;
        }

        if($default){
            return config('ashop.taxes', []);
        }

        return [];
    }

    public function banner()
    {
        if (Agent::isMobile()) {
            return $this->getMeta('banner_mobile');
        } elseif (Agent::isTablet()) {
            return $this->getMeta('banner_tablet');
        } else {
            return $this->getMeta('banner_desktop');
        }
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
        return Placeholder::width(config('ashop.categories.images.width', 800))
            ->height(config('ashop.categories.images.height', 900))
            ->text($this->display_name, ['size'  =>  60, 'angle'     =>  45])
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

    public function excerpt($limit = 200)
    {
        return str($this->description)->limit($limit);
    }

    public function name()
    {
        return $this->display_name ? $this->display_name : $this->name;
    }

    public function parentCategoryIds()
    {
        $parentIds = [];
        $this->recursiveGetParentIds($this, $parentIds);

        return $parentIds;
    }

    private function recursiveGetParentIds($category, &$parentIds)
    {
        if ($category->parent) {
            $parentIds[] = $category->parent->id;
            $this->recursiveGetParentIds($category->parent, $parentIds);
        }
    }
}
