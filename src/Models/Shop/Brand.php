<?php

namespace Takshak\Ashop\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the user that owns the Brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        return Placeholder::width(config('ashop.brands.images.width', 800))
            ->height(config('ashop.brands.images.height', 900))
            ->text($this->display_name)
            ->url();
    }
}
