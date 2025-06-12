<?php

namespace Takshak\Ashop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Takshak\Ashop\Models\Shop\Address;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\Product;
use Takshak\Ashop\Models\Shop\SearchedTerm;

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
     * The favorites that belong to the AshopUserTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites(): BelongsToMany
    {
        return $this->wishlistItems();
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

    /**
     * Get all of the searchedTerms for the AshopUserTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function searchedTerms(): HasMany
    {
        return $this->hasMany(SearchedTerm::class);
    }

    /**
     * Get all of the orders for the AshopUserTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function hasRole(string $role)
    {
        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }
        return $this->roles->contains('name', $role);
    }
}
