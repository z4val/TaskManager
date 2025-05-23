<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'limit_date',
        'is_complete'
    ];

    public function taskList() : BelongsTo
    {
        return $this->belongsTo(TaskList::class);
    }
}
