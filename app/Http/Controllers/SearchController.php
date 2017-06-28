<?php

namespace Social\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDO;
use Social\Models\User;
use Social\Http\Requests;
use Social\Http\Controllers\Controller;
use DateTime;

class SearchController extends Controller
{

    public function getResults(Request $request)
    {
        $query = $request->input('query');
        //dd($query);

        if(! $query) {
            return redirect()->route('home');
            //return redirect('/');
        }

        // Laravel with PDO
        $pdo = DB::connection()->getPdo();

        $sql = "SELECT * FROM users where CONCAT(first_name, ' ',  last_name)  like :search1 or username like :search2";

        $q = $pdo->prepare($sql);
        $q->execute(array(':search1' => '%'.$query.'%', ':search2' => '%'.$query.'%')); // need to be an array
        $users = $q->fetchAll(PDO::FETCH_CLASS, "Social\Models\User");
        //dd($users);
        return view('search.results')->with('users' ,$users);
    }
}

