<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Board extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'title',
        'user_id',
        'icon',
        'background',
        'position',
        'public',
        'dark',
    ];
}
