<?php

namespace Takshak\Ashop\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected function subtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->product->price * $this->quantity;
            }
        );
    }

    protected function subtotalNetPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->product->net_price * $this->quantity;
            }
        );
    }

    protected function discountNetPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                return ($this->product->net_price * $this->quantity) - ($this->product->price * $this->quantity);
            }
        );
    }


    /**
     * Get the product that owns the Cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeCurrentUser(Builder $query)
    {
        $query->where(function ($query) {
            $query->where('user_id', auth()->id());
            $query->orWhere('user_ip', request()->ip());
        });
    }
}
