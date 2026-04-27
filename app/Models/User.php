<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'slug', 'email', 'password', 'profession', 'photo_profile', 'bio'];

    protected $appends = ['photo_profile_url'];

    public function getPhotoProfileUrlAttribute()
    {
        if (!empty($this->attributes['photo_profile'])) {
            return url('storage/' . $this->attributes['photo_profile']);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->attributes['name'] ?? 'User');
    }

    // Prevent mass assignment for sensitive fields
    protected $guarded = ['role', 'banned', 'id', 'created_at', 'updated_at'];

    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->slug = Str::slug($user->name . '-' . uniqid());
        });
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
