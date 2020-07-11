<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Pertanyaan;
use App\VotePertanyaan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $pertanyaan = Pertanyaan::all();
        // $vote = new VotePertanyaan;
        // return view('pertanyaan.index', compact('pertanyaan', 'vote'));
        return view('index');
    }
}
