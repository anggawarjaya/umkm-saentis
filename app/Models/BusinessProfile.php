<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessProfile extends Model
{
    use HasFactory;

    function category_business() : BelongsTo {
        return $this->belongsTo(CategoryBusiness::class); 
    }

    function hamlet() : BelongsTo {
        return $this->belongsTo(Hamlet::class); 
    }

    function products() : HasMany {
        return $this->hasMany(Product::class);
    }
}
