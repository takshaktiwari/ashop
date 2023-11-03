<?php

namespace Takshak\Ashop\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ShopMeta extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getValue()
    {
        return json_decode($this->value)
            ? collect(json_decode($this->value))
            : $this->value;
    }
}
