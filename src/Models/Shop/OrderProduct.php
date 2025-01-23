<?php

namespace Takshak\Ashop\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Takshak\Ashop\Traits\AshopModelTrait;
use Takshak\Imager\Facades\Placeholder;

class OrderProduct extends Model
{
    use HasFactory, AshopModelTrait;
    protected $guarded = [];

    protected $casts = [
        'others' => 'collection'
    ];

    protected function subtotal(): Attribute
    {
        return Attribute::make(
            get: function(){
                return $this->price * $this->quantity;
            }
        );
    }

    /**
     * Get the order that owns the OrderProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the OrderProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function image()
    {
        return ($this->image && Storage::disk('public')->exists($this->image))
            ? storage($this->image)
            : $this->placeholderImage();
    }

    public function placeholderImage()
    {
        return Placeholder::width(config('ashop.products.images.width', 200))
            ->height(config('ashop.products.images.height', 225))
            ->text($this->name)
            ->url();
    }
}
