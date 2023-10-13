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
                : 'https://via.placeholder.com/800x900?text=No+Image';
    }

    public function image_md()
    {
        return ($this->image_md && Storage::disk('public')->exists($this->image_md))
                ? storage($this->image_md)
                : 'https://via.placeholder.com/400x450?text=No+Image';
    }

    public function image_sm()
    {
        return ($this->image_sm && Storage::disk('public')->exists($this->image_sm))
                ? storage($this->image_sm)
                : 'https://via.placeholder.com/200x225?text=No+Image';
    }

}
