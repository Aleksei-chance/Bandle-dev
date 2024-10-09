<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandleSortType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'value', 'sort', 'publish', 'hidden'
    ];

    public function active() {
        return $this->where('publish', '1')->where('hidden', '0')->get();
    }

    public static function get() {
        return self::query()->where('publish', '1')->where('hidden', '0')->get()->toArray();
    }
}
