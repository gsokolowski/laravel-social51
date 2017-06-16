<?php

namespace Social\Http\Controllers;

use Auth;
use Social\Models\User;

use Illuminate\Http\Request;
use Social\Http\Requests;
use Social\Http\Controllers\Controller;


class AuthController extends Controller
{


    // register form
    public function getSignup() {
        // here will be pointing to form to sign up //register
        return view('auth.signup');
    }

    // register post
    public function postSignup(Request $request) {
        // posting data through this to sign user up
        //dd($request->request);


        // if data passed will not be valid this will redirect us back to the same action postSignup() but with
        // error data passed by validator
        // you have to handle these errors on form as css class has-error
        $this->validate($request, [
            'email' => 'required|unique:users|email|max:255',
            'username' => 'required|unique:users|alpha_dash|max:20',
            'password' => 'required|min:6',
        ]);

        // store data in User table
        // use user model

        $user = new User();
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));
        //print_r($user);
        $user->save();

        // redirect
        return redirect()->route('home')->with('info', 'You have sign up!');
    }


    //login form
    public function getSignin() {
        return view('auth.signin');
    }



    public function postSignin(Request $request) {

        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        // Attemt to use Auth fasade and check if you can sign in user with email and password
        if( !Auth::attempt( $request->only( ['email', 'password'] ), $request->has('remember')) ) {
            return redirect()->back()->with('info', 'Could not sign you in with those details.');

        }

        return redirect()->route('home')->with('info', 'You are now sign in.');
    }

    public function getSignout() {

        Auth::logout();
        return redirect()->route('home')->with('info', 'You are sign out.');
    }

}


