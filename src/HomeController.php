<?php

namespace App\Http\Controllers;

use App\DTO\modelName;
use App\Helpers\Internal;
use DB;
use Illuminate\Http\Request;
use Route;
use JWTAuth;
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
    public function index($table=false)
    {

    }

}
