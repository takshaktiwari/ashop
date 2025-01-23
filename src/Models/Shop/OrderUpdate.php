<?php

namespace Takshak\Ashop\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class OrderUpdate extends Model
{
    use HasFactory;
    protected $guarded = [];

protected static function boot()
{
    parent::boot();

    static::updating(function ($model) {
        $model->payment_status = $model->payment_status ?? false;
    });
}


    public function orderStatus()
    {
        return config('ashop.order.status.' . $this->order_status);
    }

    public function paymentStatus()
    {
        return $this->payment_status ? 'Paid' : 'Not Paid';
    }
}
