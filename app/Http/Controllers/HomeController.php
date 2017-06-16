<?php

namespace Social\Http\Controllers;

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
        return view('home');
    }


}
