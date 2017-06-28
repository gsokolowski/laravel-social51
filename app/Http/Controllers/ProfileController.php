<?php
/* This is User profile controller */
namespace Social\Http\Controllers;

use Auth;
use Social\Models\User;
use Illuminate\Http\Request;

use Social\Http\Requests;
use Social\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfile($username)
    {

        $user = User::where('username', $username)->first();
        if (! $user) {
            abort(404);
        }

        //dd($username);
        return view('profile.index')->with('user', $user);
    }

    public function getEdit() {
        // will return only a view with form to edit

        return view('profile.edit');
    }

    public function postEdit(Request $request) {

        $this->validate($request, [

            'first_name' => 'alpha|max:50',
            'last_name' => 'alpha|max:50',
            'location' => 'max:20',
        ]);

        //dd('validation ok');


        // update user profile
        Auth::user()->update([
           'first_name' => $request->input('first_name'),
           'last_name' => $request->input('last_name'),
           'location' => $request->input('location'),
        ]);

        return redirect()->route('profile.edit')
                         ->with('info', 'Your profile has been updated');
    }
}
