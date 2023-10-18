<?php

namespace Takshak\Ashop\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the product that owns the ProductImage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function image_lg()
    {
        return ($this->image_lg && Storage::disk('public')->exists($this->image_lg))
            ? storage($this->image_lg)
            : $this->placeholderImage();
    }
    public function image_md()
    {
        return ($this->image_md && Storage::disk('public')->exists($this->image_md))
            ? storage($this->image_md)
            : $this->placeholderImage();
    }
    public function image_sm()
    {
        return ($this->image_sm && Storage::disk('public')->exists($this->image_sm))
            ? storage($this->image_sm)
            : $this->placeholderImage();
    }
    public function placeholderImage()
    {
        return Placeholder::width(config('ashop.products.images.width', 800))
            ->height(config('ashop.products.images.height', 900))
            ->text($this->display_name)
            ->url();
    }
    public function image($size = null)
    {
        if ($size == 'sm') {
            return $this->image_sm();
        } elseif ($size == 'md') {
            return $this->image_md();
        } elseif ($size == 'lg') {
            return $this->image_lg();
        } else {
            return $this->image_md();
        }
    }
}
