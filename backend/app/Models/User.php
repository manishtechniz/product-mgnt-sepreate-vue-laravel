<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Summary of timestamps
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'password'          => 'hashed',
        ];
    }

    /**
     * Disclose custom attribute for json data.
     */
    protected $appends = ['first_name', 'last_name'];

    /**
     * Get the user's first name.
     */
    public function getFirstNameAttribute(): string
    {
        return explode(' ', $this->name)[0];
    }

    /**
     * Get the user's last name.
     */
    public function getLastNameAttribute(): string
    {
        $nameParts = explode(' ', $this->name);

        /**
         * If the name has more than one part, return the last part including middle name.
         */
        return count($nameParts) > 1 
            ? implode(' ', array_slice($nameParts, 1)) 
            : '';
    }
}
