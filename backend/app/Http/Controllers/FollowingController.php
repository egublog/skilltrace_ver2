<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Follow;

class FollowingController extends Controller
{
    public function index($userId)
    {
        $myId = Auth::id();
        $followings = Follow::where('user_id', $userId)->get();

        return view('MyService.friends_list', compact('myId', 'followings', 'userId'));
    }

    public function follow($userId)
    {
        $myId = Auth::id();
        $myAccount = User::find($myId);

        $myAccount->follow()->attach($userId);
    }

    public function unfollow($userId)
    {
        $myId = Auth::id();
        $myAccount = User::find($myId);

        $myAccount->follow()->detach($userId);
    }
}
