<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $table = 'table_house';
    protected $fillable = [
        'name',
        'price_rub',
        'price_usd',
        'default_currency',
        'floors',
        'bedrooms',
        'area',
        'object_type',
        'image_gallery',
        'village_id'
    ];

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
