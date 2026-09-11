<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'property_id', 'id');
    }

    public function bestOffer(): HasOne
    {
        return $this->hasOne(Offer::class, 'property_id', 'id')
            ->orderBy('price', 'ASC');
    }
}
