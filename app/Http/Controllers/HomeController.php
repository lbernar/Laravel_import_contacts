<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\CsvData;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CsvData::select('csv_filename', 'csv_status')->where('user_id', Auth::user()->id)->get();
        $csv_data = json_decode($data, true);
        return view('home', compact('csv_data'));
    }
}
