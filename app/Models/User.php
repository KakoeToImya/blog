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

    public function friends()
    {
        $friendsId= Friendship::where('status','accepted')
        ->where(function ($query) {
           $query->where('user_id', $this->id)->orWhere('friend_id',$this->id);
        })->get()->map(function ($friend){
            $friend=[$friend->user_id,$friend->friend_id];
            return $friend;
        })->toArray();


        $friends = User::where('id','!=',$this->id)->whereIn('id', array_merge(...$friendsId))->get();


        return $friends;
    }

    public function pendingFriends(){
        return $this->receivedFriend()->where('status','pending')->with('user');
    }

    public function sentPendingFriends(){
        return $this->sentFriend()->where('status','pending')->with('user');
    }

    public function isFriend(User $user){
        $friendship = Friendship::where('status', 'accepted')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $this->id)->where('friend_id', $user->id);
            })->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('friend_id', $this->id)->where('status', 'accepted');
            })->first();

        return !is_null($friendship);
    }

    public function sentFriendRequest(User $user){
            return $this->sentFriend()->where('friend_id', $user->id)->where('status','pending')->exists();
    }

    public function receivedFriendRequest(User $user){
        return $this->receivedFriend()->where('user_id',$user->id)->where('status','pending')->exists();
    }

    public function removeFriendship(User $friend)
    {
        return Friendship::where('status', 'accepted')->where(function ($query) use ($friend) {
            $query->where([
                'user_id' => $this->id,
                'friend_id' => $friend->id
            ])->orWhere([
                'user_id' => $friend->id,
                'friend_id' => $this->id
            ]);
        })->delete();
    }


}
