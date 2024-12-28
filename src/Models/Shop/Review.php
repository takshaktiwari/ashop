<?php

namespace Takshak\Ashop\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Takshak\Areviews\Models\Areviews\Review as AreviewsReview;

class Review extends AreviewsReview
{
    use HasFactory, HasEagerLimit;
    protected $guarded = [];

}
