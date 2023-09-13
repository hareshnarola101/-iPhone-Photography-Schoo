<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'badge',
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
        'password' => 'hashed',
    ];

    /**
     * get comment written by user
     */

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->withPivot('watched');
    }

    public function achievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function updateBadges()
    {
        $achievementCount = $this->achievements()->count();

        if ($achievementCount >= 10) {
            $this->update(['badge' => 'Master']);
        } elseif ($achievementCount >= 8) {
            $this->update(['badge' => 'Advanced']);
        } elseif ($achievementCount >= 4) {
            $this->update(['badge' => 'Intermediate']);
        } else {
            $this->update(['badge' => 'Beginner']);
        }

    }
}
