<?php

namespace Social\Http\Controllers;

use Auth;
use Social\Models\Status;
use Illuminate\Http\Request;

use Social\Http\Requests;
use Social\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()) {

            $statuses = Status::notReply()->where(function($query) {
                return $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('user_id', Auth::user()->friends()->lists('id'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(2);


            //dd($statuses);

            $user = Auth::user();
            return view('timeline.index')
                ->with('statuses', $statuses)
                ->with('user', $user);
        }
        return view('home');
    }


}
