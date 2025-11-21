<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use App\Http\Requests\SearchFriendsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function index(){
        $user=Auth::user();
        $friend = $user->friends()->paginate(10);
        $pending = $user->pendingFriends()->with('user')->get();
        $sent = $user->sentPendingFriends()->with('friend')->get();

        return view('friends.index',compact('friend','pending','sent'));

    }

    public function send(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Нельзя отправить запрос самому себе.');
        }

        if (Auth::user()->sentFriendRequest($user)) {
            return redirect()->back()->with('error', 'Вы уже отправили запрос этому пользователю.');
        }

        if (Auth::user()->isFriend($user)) {
            return redirect()->back()->with('error', 'Этот пользователь уже у вас в друзьях.');
        }

        if (Auth::user()->receivedFriendRequest($user)) {
            return redirect()->back()->with('error', 'Этот пользователь уже отправил вам запрос в друзья.');
        }

        Friendship::create([
            'user_id' => Auth::id(),
            'friend_id' => $user->id,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Запрос в друзья отправлен!');
    }

    public function accept(User $user)
    {
        $friendship = Friendship::where('user_id', $user->id)->where('friend_id', Auth::id())->where('status', 'pending')->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Запрос в друзья не найден.');
        }

        $friendship->update(['status' => 'accepted']);

        return redirect()->back()->with('success', 'Запрос в друзья принят!');
    }

    public function reject(User $user)
    {
        $friendship = Friendship::where('user_id', $user->id)->where('friend_id', Auth::id())->where('status', 'pending')->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Запрос в друзья не найден.');
        }

        $friendship->delete();

        return redirect()->back()->with('success', 'Запрос в друзья отклонен.');
    }

    public function cancel(User $user)
    {
        $friendship = Friendship::where('user_id', Auth::id())->where('friend_id', $user->id)->where('status', 'pending')->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Запрос в друзья не найден.');
        }

        $friendship->delete();

        return redirect()->back()->with('success', 'Запрос в друзья отменен.');
    }

    public function remove(User $user)
    {
        if (!Auth::user()->isFriend($user)) {
            return redirect()->back()->with('error', 'Поользователь не у вас в друзьях.');
        }

        Auth::user()->removeFriendship($user);

        return redirect()->back()->with('success', 'Пользователь удален из друзей.');
    }

    public function search(SearchFriendsRequest $request)
    {
        $query = $request->validated()['query'];

        $users = User::where('id', '!=', Auth::id())->where(function ($q) use ($query) {

            $q->where('name', 'LIKE', "%{$query}%")->orWhere('email', 'LIKE', "%{$query}%");
        })->paginate(10);

        return view('friends.search', compact('users', 'query'));
    }
}
