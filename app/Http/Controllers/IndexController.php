<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use File;

class IndexController extends Controller
{
    public function index()
    {
      $path = 'data/motive/';
      $files = File::files(public_path($path));
      $index = array_rand($files);
      $background = asset($path.$files[$index]->getFilename());
      return Inertia::render('Index', [
        'background' => $background
      ]);
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
