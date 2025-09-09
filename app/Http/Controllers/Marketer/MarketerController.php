<?php

namespace App\Http\Controllers\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:marketer');
    }

    /**
     * Show the marketer dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('marketer.dashboard.main');
    }
}
