<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Board;
use App\Models\Column;
use App\Models\Card;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        Board::create([
            'user_id' => User::first()->id,
            'title' => 'Games',
            'icon' => 'https://img.like.video/eu_live/3u1/M0B/1E/C8/CDIOAGPj5XOIA28rAACx1_3VsMcAE27ngHi59gAALHv728.png',
            'background' => 'https://w-dog.ru/wallpapers/7/3/438747139195550/gejdzh-mechromancer-borderlands-ruka.jpg',
            'position' => 0,
            'public' => true,
            'dark' => true,
        ]);

        Board::create([
            'user_id' => User::first()->id,
            'title' => 'Work',
            'icon' => 'https://lh3.googleusercontent.com/AIHSek946ucNx2mEiaqtySXfzOLMDlAVDbmM3-TRPRSM35wy4lZzsWc_wqMqPGcWWfTe=w300',
            'background' => 'https://wallpapers.com/images/hd/minimalist-motivational-your-future-biz5yachn4r6clmi.jpg',
            'public' => false,
            'dark' => false,
        ]);

        Column::create([
            'title' => 'Wanna Play',
            'board_id' => Board::first()->id,
            'public' => true,
        ]);

        Column::create([
            'title' => 'Playing',
            'board_id' => Board::first()->id,
            'public' => true,
        ]);

        Column::create([
            'title' => 'Done',
            'board_id' => Board::first()->id,
            'public' => true,
        ]);


        Column::create([
            'title' => 'To Do',
            'board_id' => Board::where('title', 'Work')->first()->id,
        ]);

        Column::create([
            'title' => 'Doing',
            'board_id' => Board::where('title', 'Work')->first()->id,
        ]);

        Column::create([
            'title' => 'Done',
            'board_id' => Board::where('title', 'Work')->first()->id,
        ]);


        Card::create([
            'column_id' => Column::first()->id,
            'title' => 'Honkai: Star Rail',
            'cover' => 'https://true-game.ru/wp-content/uploads/oxy.sports-2-1024x594.png',
            'description' => "- Task 1 \n - Task 2",
        ]);

    }
}
