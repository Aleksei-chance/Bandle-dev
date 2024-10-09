<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class bandle extends Model
{
    use HasFactory;

    protected $table = 'bandles';

    protected $fillable = [
        'user_id', 'title', 'description', 'publish', 'hidden'
    ];

    protected $casts = [
        'user_id' => 'integer'
        , 'publish' => 'boolean'
        , 'hidden' => 'boolean'
    ];

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'bandle_id')->where('publish', '1')->where('hidden', '0');
    }

    public function blocks_count($block_type_id = 0)
    {
        $query = $this->hasMany(Block::class, 'bandle_id')->where('publish', '1')->where('hidden', '0');
        if($block_type_id > 0) {
            return $query->where('block_type_id', $block_type_id)->count();
        } else {
            return $query->count();
        }
        
    }
}
