<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function indexAdmin(){
        return Inertia::render('Admin/Index');
    }

    public function indexStaff(){
        return Inertia::render('Staff/Index');
    }

    public function indexUser(){
        return Inertia::render('Dashboard');
    }
}
