<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Column extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'title',
        'board_id',
        'position',
        'public',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'column_id');
    }
}
