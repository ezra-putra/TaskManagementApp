<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'assign_tasks')->withPivot('task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
