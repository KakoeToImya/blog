<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(){
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id){
        $user = Auth::user();
        $notification =  $user->notifications()->where('id', $id)->first();

        if($notification){
            $notification->markAsRead();
        }

        return redirect()->back()->with('success', 'уведомление помечено прочитанным');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return redirect()->back()->with('success', 'уведомления помечены прочитанными');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $user->notifications()->where('id', $id)->delete();

        return redirect()->back()->with('success', 'уведомление удалено');

    }

    public function clearAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();

        return redirect()->back()->with('success', 'все уведомления удалены.');
    }
}
