<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Obtener el usuario autenticado
        $user = Auth::user();
        // $role = $user->role->first()->name;

        // dd($user->roles);

        return view('home', [
            'user' => $user,
            'canCreateRole' => $user->can('create_role'),
        ]);
    }
}
