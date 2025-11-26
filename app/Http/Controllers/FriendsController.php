<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use App\Http\Requests\SearchFriendsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FriendRequestNotification;
use App\Notifications\acceptedNotificationRequest;

class FriendsController extends Controller
{
    public function index(){
        $user=Auth::user();
        $friends = $user->friends();
        $pending = $user->pendingFriends()->with('user')->get();
        $sent = $user->sentPendingFriends()->with('friend')->get();

        return view('friends.index',compact('friends','pending','sent'));

    }

    public function send(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return redirect()->back()->with('error', 'Нельзя отправить запрос самому себе.');
        }

        if ($currentUser->sentFriendRequest($user)) {
            return redirect()->back()->with('error', 'Вы уже отправили запрос этому пользователю.');
        }

        if ($currentUser->isFriend($user)) {
            return redirect()->back()->with('error', 'Этот пользователь уже у вас в друзьях.');
        }

        if ($currentUser->receivedFriendRequest($user)) {
            return redirect()->back()->with('error', 'Этот пользователь уже отправил вам запрос в друзья.');
        }

        $friendship = Friendship::create([
            'user_id' => $currentUser->id,
            'friend_id' => $user->id,
            'status' => 'pending'
        ]);

        $user->notify(new FriendRequestNotification($currentUser));

        return redirect()->back()->with('success', 'Запрос в друзья отправлен!');
    }

    public function accept(User $user)
    {
        $currentUser = Auth::user();

        $friendship = Friendship::where('user_id', $user->id)->where('friend_id', $currentUser->id)->where('status', 'pending')->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Запрос в друзья не найден.');
        }

        $friendship->update(['status' => 'accepted']);

        $user->notify(new acceptedNotificationRequest($currentUser));


        return redirect()->back()->with('success', 'Запрос в друзья принят!');
    }

    public function reject(User $user)
    {
        $currentUser = Auth::user();

        $friendship = Friendship::where('user_id', $user->id)->where('friend_id', $currentUser->id)->where('status', 'pending')->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Запрос в друзья не найден.');
        }

        $friendship->delete();

        return redirect()->back()->with('success', 'Запрос в друзья отклонен.');
    }

    public function cancel(User $user)
    {
        $currentUser = Auth::user();

        $friendship = Friendship::where('user_id', $currentUser->id)->where('friend_id', $user->id)->where('status', 'pending')->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Запрос в друзья не найден.');
        }

        $friendship->delete();

        return redirect()->back()->with('success', 'Запрос в друзья отменен.');
    }

    public function remove(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isFriend($user)) {
            return redirect()->back()->with('error', 'Этот пользователь не у вас в друзьях.');
        }

        $deleted = $currentUser->removeFriendship($user);

        if ($deleted === 0) {
            return redirect()->back()->with('error', 'Произошла ошибка при удалении из друзей.');
        }

        return redirect()->back()->with('success', 'Пользователь удален из друзей.');
    }

    public function search(SearchFriendsRequest $request)
    {
        $query = $request->validated()['query'];

        $currentUserid = Auth::id();

        $users = User::where('id', '!=', Auth::id())->where(function ($q) use ($query) {

            $q->where('name', 'LIKE', "%{$query}%")->orWhere('email', 'LIKE', "%{$query}%");
        })->paginate(10);

        if(!$users->isEmpty()) {
            $this->friendshipStatus($users, $currentUserid);
        }
        return view('friends.search', compact('users', 'query'));
    }

    private function friendshipStatus($users, $currentUserId)
    {
        $usersId = $users->pluck('id');
        $friendships = Friendship::where(function ($q) use ($currentUserId, $usersId) {
            $q->where('user_id', $currentUserId)->whereIn('friend_id', $usersId)->orWhere('friend_id', $currentUserId)->whereIn('user_id', $usersId);
        })->get();

        $status = [];
        foreach($friendships as $friendship) {
            if($friendship->user_id == $currentUserId) {
                $otherUser = $friendship->friend_id;
            }
            else{
                $otherUser = $friendship->user_id;
            }

            if($friendship->status == 'accepted') {
                $status[$otherUser] = 'friend';
            }
            elseif($friendship->status == 'pending'){
                if($friendship->user_id == $currentUserId) {
                    $status[$otherUser] = 'sent';
                }
                else{
                    $status[$otherUser] = 'received';
                }
            }
        }
        foreach($users as $user) {
            $user->friendship_status = $status[$user->id]??'none';
        }
    }
}
