<?php

/*
 * This controller is for displaying friends adding new friends and deleting friends
 * */

namespace Social\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Social\Models\User;
use Illuminate\Http\Request;

use Social\Http\Requests;
use Social\Http\Controllers\Controller;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {

        $friends = Auth::user()->friends();
        $friendRequests = Auth::user()->friendRequests();
        return view('friends.index')
            ->with('friends', $friends)
            ->with('friendRequests', $friendRequests);
    }

    // create get for adding friend add friend button
    public function getAdd($username) {

        $user = User::where('username', $username)->first();

        if(!$user) {
            return redirect()
                ->route('home')
                ->with('info', 'That user could not be found');
        }

        // if you are trying to add youreself as a friend
        if (Auth::user()==$user) {
            return redirect()
                ->route('profile.index', ['username' => $user->username])
                ->with('info','You are trying to add yourself as a friend ');
        }

        // if you are already have panding request with this user
        if(Auth::user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending( Auth::user() )) {
            return redirect()
                ->route('profile.index', ['username' => $user->username])
                ->with('info','Friend request already pending ');
        }

        // if you are already a friend with this user
        if( Auth::user()->isFriendsWith($user) ) {
            return redirect()
                ->route('profile.index', ['username' => $user->username])
                ->with('info','You are already friends ');
        }


        // Add friend to current user
        Auth::user()->addFriend($user);
        return redirect()
            //->route('profile.index', ['username' => $user->username])
            ->route('profile.index', ['username' => $username])
            ->with('info','Fried request sent ');
    }

    public function getAccept($username) {

        $user = User::where('username', $username)->first();

        if(!$user) {
            return redirect()
                ->route('home')
                ->with('info', 'That user could not be found');
        }

        if(!Auth::user()->hasFriendRequestReceived($user) ) {
            return redirect()->route('home');
        }


        Auth::user()->acceptFriendRequest($user);
        return redirect()
            ->route('profile.index', ['username' => $username])
            ->with('info','Fried request accepted ');

    }
}
