<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class IndexController extends Controller
{
  public function index()
  {
    return Inertia::render('Index');
  }

  public function author()
  {
    return Inertia::render('Page', [
      'title' => 'About author',
      'content' => file_get_contents(base_path('AUTHOR.md')),
      'page' => 'author'
    ]);
  }

  public function about()
  {
    return Inertia::render('Page', [
      'title' => 'About project',
      'content' => file_get_contents(base_path('ABOUT.md')),
      'page' => 'project'
    ]);
  }
}
