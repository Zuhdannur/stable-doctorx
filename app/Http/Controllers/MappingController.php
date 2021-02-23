<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MappingController extends Controller
{
    public function index() {
        return view('pages.mapping.index');
    }
}
