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
    use HasFactory;
    use AshopModelTrait;
    protected $guarded = [];

    protected $casts = [
        'others' => 'collection'
    ];

    protected function subtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
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

    public function getPriceBreakDown()
    {
        $taxBreakups = [
            'sellPrice' => null,
            'basePrice' => null,
            'taxes' => [],
            'taxAmounts' => [],
        ];
        $currencySign = config('ashop.currency.sign', '₹');

        $this->product->load('categories.metas');
        foreach ($this->product->categories as $category) {
            $taxes = $category->metas->where('key', 'taxes')->mapWithKeys(function ($tax, $key) {
                return [$tax->name => $tax->value];
            });

            foreach ($taxes as $key => $value) {
                if (!isset($taxBreakups['taxes'][$key])) {
                    $taxBreakups['taxes'][$key] = $value;
                } elseif ($taxBreakups['taxes'][$key] < $value) {
                    $taxBreakups['taxes'][$key] = $value;
                }
            }
        }

        $taxBreakups['basePrice'] = $this->product->sell_price / (1 + array_sum($taxBreakups['taxes']) / 100);
        foreach ($taxBreakups['taxes'] as $key => $percent) {
            $taxBreakups['taxAmounts'][$key]['percent'] = $percent;

            $taxAmount = $taxBreakups['basePrice'] * ($percent / 100);
            $taxAmount = $taxAmount * $this->quantity;
            $taxBreakups['taxAmounts'][$key]['amount'] =  $currencySign . number_format($taxAmount, 2);
        }

        $taxBreakups['subtotal'] = $currencySign . number_format($taxBreakups['basePrice'] * $this->quantity, 2);
        $taxBreakups['basePrice'] = $currencySign . number_format($taxBreakups['basePrice'], 2);

        return $taxBreakups;
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
