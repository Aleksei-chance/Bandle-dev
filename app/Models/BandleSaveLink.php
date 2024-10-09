<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandleSaveLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bandle_id', 'publish', 'hidden'
    ];
}
