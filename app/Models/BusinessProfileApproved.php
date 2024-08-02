<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessProfileApproved extends Model
{
    use HasFactory;

    protected $table = 'business_profiles';

    function category_business() : BelongsTo {
        return $this->belongsTo(CategoryBusiness::class); 
    }

    function hamlet() : BelongsTo {
        return $this->belongsTo(Hamlet::class); 
    }

    function products() : HasMany {
        return $this->hasMany(Product::class);
    }

    function user() : BelongsTo {
        return $this->belongsTo(User::class); 
    }

    protected $casts = [
        'location' => 'array', // Important for Array type
    ];
}
