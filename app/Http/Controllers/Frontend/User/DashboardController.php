<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Bouncer;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
    	$user = User::find(1);
    	Bouncer::allow($user)->to('view backend');

        return view('frontend.user.dashboard');
    }
}
