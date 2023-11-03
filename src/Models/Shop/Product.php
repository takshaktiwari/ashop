<?php

namespace Takshak\Ashop\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Takshak\Imager\Facades\Placeholder;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'deal_expiry'   =>  'date',
        'sell_price'   =>  'decimal:2',
        'net_price'   =>  'decimal:2',
    ];

    /**
     * The categories that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the brand that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the user that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the images for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * The wishlistUsers that belong to the AshopUserTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wishlistUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlist_items');
    }

    /**
     * The wishlistAuthUser that belong to the AshopUserTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wishlistAuthUser(): BelongsToMany
    {
        return $this->wishlistUsers()->where('wishlist_items.user_id', auth()->id());
    }

    /**
     * Get the product that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function productParent()
    {
        return $this->product();
    }

    /**
     * Get all of the products for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function productChildren()
    {
        return $this->products();
    }

    /**
     * Get all of the productVariations for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get all of the productVariations for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variationProperties(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'variant_product_id');
    }

    public function metas(): MorphMany
    {
        return $this->morphMany(ShopMeta::class, 'metable');
    }

    public function scopeParent(Builder $query)
    {
        return $query->whereNull('product_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('featured', true);
    }


    public function details($name = null, $value = true)
    {
        if (!$name) {
            return $this->metas;
        }

        $meta = $this->metas->where('name', $name)->first();
        return $value ? $meta?->value : $meta;
    }

    public function formattedSellPrice($value = '')
    {
        return config('ashop.currency.sign', '₹') . number_format($this->sell_price, 2);
    }

    public function formattedNetPrice($value = '')
    {
        return config('ashop.currency.sign', '₹') . number_format($this->net_price, 2);
    }

    public function formattedDealPrice($value = '')
    {
        if($this->deal_price && $this->deal_expiry > now()) {
            return config('ashop.currency.sign', '₹') . number_format($this->deal_price, 2);
        }
    }

    public function formattedPrice($value = '')
    {
        if($this->deal_price && $this->deal_expiry > now()) {
            return config('ashop.currency.sign', '₹') . number_format($this->deal_price, 2);
        } else {
            return $this->formattedSellPrice();
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
        return Placeholder::width(config('ashop.products.images.width', 800))
            ->height(config('ashop.products.images.height', 900))
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
