<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
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
            'password' => 'hashed',
        ];
    }

    public function comments(): HasMany{
        return $this->hasMany(Comment::class);
    }

    public function posts(): HasMany{
        return $this->hasMany(Post::class);
    }

    public function commentedPosts(): BelongsToMany{
        return $this->belongsToMany(Post::class, 'comments');
    }


    public function sentFriend(): HasMany{
        return $this->hasMany(Friendship::class,'user_id');
    }

    public function receivedFriend(): HasMany
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }

    public function friends(): BelongsToMany{
        $sentFriends = $this->belongsToMany(User::class, 'friendship', 'user_id', 'friend_id')->withPivot('status','accepted');
        $receivedFriends = $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')->withPivot('status','accepted');

        return $sentFriends->union($receivedFriends);
    }

    public function pendingFriends(){
        return $this->sentFriend()->where('status','pending');
    }

    public function sentPendingFriends(){
        return $this->receivedFriend()->where('status','pending');
    }

    public function isFriend(User $user){
        return $this->friends()->where('user_id', $user->id)->$this->exists();
    }

    public function sentFriendRequest(User $user){
        return $this->sentFriend()->where('friend_id', $user->id)->where('status','pending')->exists();
    }

    public function receivedFriendRequest(User $user){
        return $this->receivedFriend()->where('user_id',$user->id)->where('status','pending')->exists();
    }

    public function removeFriendship(User $friend)
    {
        $sent = $this->sentFriendRequests()->where('friend_id', $friend->id)->where('status', 'accepted')->delete();

        $received = $this->receivedFriendRequests()->where('user_id', $friend->id)->where('status', 'accepted')->delete();

        if(!!$sent && !!$received){
            return true;
        }
    }
}
