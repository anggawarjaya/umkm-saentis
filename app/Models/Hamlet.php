<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Hamlet extends Model
{
    use HasFactory;

    public function business_profiles(): HasMany
    {
        return $this->hasMany(BusinessProfile::class);
    }

    public function chief_hamlet(): HasOne
    {
        return $this->hasOne(ChiefHamlet::class);
    }
}
