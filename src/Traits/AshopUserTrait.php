<?php

namespace Takshak\Ashop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Takshak\Ashop\Models\Shop\Address;
use Takshak\Ashop\Models\Shop\Product;

trait AshopUserTrait
{
    /**
     * The wishlistItems that belong to the AshopUserTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wishlistItems(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlist_items')->withTimestamps();
    }

    /**
     * Get all of the addresses for the AshopUserTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
