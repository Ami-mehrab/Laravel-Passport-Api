<?php

namespace App\Models;

// 1. MAKE SURE THIS IS IMPORTED
use Illuminate\Support\Facades\Storage; 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar', 
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 2. ADD 'avatar' TO THIS ARRAY
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar']; // This tells Laravel to add the avatar URL

    /**
     * 3. ADD THIS ENTIRE FUNCTION
     * * Get the user's avatar URL.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getAvatarAttribute($value)
    {
        // This function checks the 'avatar' column in your database.
        // If it has a value (like 'avatars/my-photo.png')
        // and that file exists, it builds the full public URL.
        if ($value && Storage::disk('public')->exists($value)) {
            // Returns: "http://localhost:8000/storage/avatars/my-photo.png"
            return Storage::disk('public')->url($value);
        }
        
        // If the 'avatar' column is empty or the file is missing,
        // it returns null. Your JavaScript will then show the user's initial.
        return null;

        // ---
        // OPTIONAL: If you have a default avatar image, you can return its URL instead:
        // return 'http://localhost:8000/storage/avatars/default.png';
        // ---
    }
}