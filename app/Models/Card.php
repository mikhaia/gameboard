<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'title',
        'column_id',
        'cover',
        'description',
        'progress',
        'link',
        'position',
        'todo',
    ];

    public function columns(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'column_id', 'id');
    }
}
