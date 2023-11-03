<?php

namespace Takshak\Ashop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        return $this->belongsToMany(Product::class, 'wishlist_items');
    }
}
