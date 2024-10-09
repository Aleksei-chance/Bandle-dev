<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bandles(): HasMany
    {
        return $this->hasMany(Bandle::class, 'user_id')->where('publish', '1')->where('hidden', '0');
    }
    
    public function bandles_saved(): HasMany
    {
        return $this->hasMany(BandleSaveLink::class, 'user_id')->where('bandle_save_links.publish', '1')->where('bandle_save_links.hidden', '0')
        ->join('bandles', 'bandle_save_links.bandle_id', '=', 'bandles.id')->select('bandles.*');
    }
}
