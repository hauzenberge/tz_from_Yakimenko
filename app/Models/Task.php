<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Subtask;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'status'
    ];

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
