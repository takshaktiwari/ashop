<?php

namespace Takshak\Ashop\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $lastOrder = Order::orderBy('id', 'DESC')->first();
            $model->order_no = str(now()->format('ymd'))->append((int)$lastOrder?->id + 1);
        });
    }

    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->subtotal + $this->shipping_charge - $this->discount;
            }
        );
    }

    /**
     * Get the user that owns the Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the products for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function paymentStatus()
    {
        return $this->payment_status ? 'Paid' : 'Not Paid';
    }

    public function orderStatus()
    {
        return config('ashop.order.status.' . $this->order_status);
    }

    public function address($lines = 3)
    {
        $address = $this->name . ', ';
        $address .= $this->mobile . ', ';
        if ($lines > 1) {
            $address .= '<br />';
        }
        $address .= $this->address_line_1 . ', ';
        if ($this->address_line_2) {
            $address .= $this->address_line_2 . ', ';
        }
        if ($this->landmark) {
            $address .= $this->landmark . ', ';
        }
        if ($lines > 2) {
            $address .= '<br />';
        }
        $address .= $this->city . ', ';
        $address .= $this->pincode . ', ';
        $address .= $this->state . ', ';
        $address .= $this->country;
        return $address;
    }
}
