<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show($userId)
    {
      exit();
      /*
      return Inertia::render('Show', [
          'user' => Auth::user()
      ]);
      */
    }
}