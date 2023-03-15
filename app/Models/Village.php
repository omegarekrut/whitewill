<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $table = 'table_village';
    protected $fillable = [
        'name',
        'address',
        'area',
        'hotline',
        'youtube_video',
        'photo',
        'presentation_file',
    ];

    public function houses()
    {
        return $this->hasMany(House::class);
    }
}
