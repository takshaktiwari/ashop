<?php

namespace Takshak\Ashop\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Takshak\Ashop\Traits\AshopModelTrait;

class Order extends Model
{
    use HasFactory, AshopModelTrait;
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
                return round($this->subtotal + $this->shipping_charge - $this->discount, 2);
            }
        );
    }

    protected function shippingCharge(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return round($value, 2);
            }
        );
    }

    protected function discount(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return round($value, 2);
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

    /**
     * Get the orderUpdate associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderUpdate(): HasOne
    {
        return $this->hasOne(OrderUpdate::class)->latest();
    }

    /**
     * Get all of the orderUpdates for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderUpdates(): HasMany
    {
        return $this->hasMany(OrderUpdate::class);
    }

    public function paymentStatus()
    {
        return $this->payment_status ? 'Paid' : 'Not Paid';
    }

    public function paymentMode()
    {
        return $this->payment_mode == 'cod' ? 'COD' : str($this->payment_mode)->title();
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
        $address .= $this->state . ', ';
        if ($lines > 3) {
            $address .= '<br />';
        }
        $address .= '['.$this->pincode . '], ';
        $address .= $this->country;
        return $address;
    }

    public function cancellable()
    {
        $cancellable = true;
        if(!in_array($this->order_status, config('ashop.order.cancel.order_status', []))) {
            return false;
        }

        foreach ($this->orderProducts as $oProduct) {
            if (!config('ashop.order.cancel.status')) {
                $cancellable = false;
                break;
            } elseif (!$oProduct->product->getDetail('cancellable')) {
                $cancellable = false;
                break;
            } else {
                $cancel_within = $oProduct->product->getDetail(
                    name: 'cancel_within',
                    default: config('ashop.order.cancel.within', 0)
                );
                if ($this->created_at->addDays((int)$cancel_within) < now()) {
                    $cancellable = false;
                    break;
                }
            }
        }

        return $cancellable;
    }

    public function returnable()
    {
        $returnable = true;
        if(!in_array($this->order_status, config('ashop.order.return.order_status', []))) {
            return false;
        }

        foreach ($this->orderProducts as $oProduct) {
            if (!config('ashop.order.return.status')) {
                $returnable = false;
                break;
            } elseif (!$oProduct->product->getDetail('returnable')) {
                $returnable = false;
                break;
            } else {
                $return_within = $oProduct->product->getDetail(
                    name: 'return_within',
                    default: config('ashop.order.return.within', 0)
                );
                if ($this->created_at->addDays((int)$return_within) < now()) {
                    $returnable = false;
                    break;
                }
            }
        }

        return $returnable;
    }

    public function replaceable()
    {
        $replaceable = true;
        if(!in_array($this->order_status, config('ashop.order.replace.order_status', []))) {
            return false;
        }

        foreach ($this->orderProducts as $oProduct) {
            if (!config('ashop.order.replace.status')) {
                $replaceable = false;
                break;
            } elseif (!$oProduct->product->getDetail('replaceable')) {
                $replaceable = false;
                break;
            } else {
                $replace_within = $oProduct->product->getDetail(
                    name: 'replace_within',
                    default: config('ashop.order.replace.within', 0)
                );
                if ($this->created_at->addDays((int)$replace_within) < now()) {
                    $replaceable = false;
                    break;
                }
            }
        }

        return $replaceable;
    }
}
