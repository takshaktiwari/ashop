<?php

namespace Takshak\Ashop\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Takshak\Ashop\Traits\AshopModelTrait;

class Coupon extends Model
{
    use HasFactory, AshopModelTrait;
    protected $guarded = [];
    protected $casts = [
        'expires_at' => 'date',
        'percent' => 'decimal:2',
        'amount' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    /**
     * The users that belong to the Coupon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function scopeActive(Builder $query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('featured', true);
    }

    public function formattedAmount($value = '')
    {
        if ($this->amount) {
            return config('ashop.currency.sign', '₹').number_format($this->amount, 2);
        }
    }
    public function formattedPercent($value = '')
    {
        if ($this->percent) {
            return number_format($this->percent, 2).'%';
        }
    }
    public function formattedMinPurchase($value = '')
    {
        if ($this->min_purchase) {
            return config('ashop.currency.sign', '₹').number_format($this->min_purchase, 2);
        }
    }
    public function formattedMaxDiscount($value = '')
    {
        if ($this->max_discount) {
            return config('ashop.currency.sign', '₹').number_format($this->max_discount, 2);
        }
    }
}
