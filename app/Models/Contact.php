<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_id', 'user_id', 'contact_type_id', 'value', 'sort', 'publish', 'hidden'
    ];
}
