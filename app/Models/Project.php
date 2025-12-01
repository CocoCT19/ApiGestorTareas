<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'priority',
        'is_archived'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
