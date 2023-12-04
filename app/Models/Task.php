<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PriorityEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Task",
 *     type="object",
 *     required={"status", "priority", "title"}
 * )
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'priority', 'title', 'description', 'createdAt', 'completedAt', 'user_id', 'task_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function setPriorityAttribute($value)
    {
        $this->attributes['priority'] = PriorityEnum::from($value)->value;
    }

    public function getPriorityAttribute($value)
    {
        return PriorityEnum::from($value);
    }

    public function setDefaultPriorityAttribute()
    {
        $this->attributes['priority'] = PriorityEnum::defaultValue()->value;
    }

    public function hasUncompletedSubtasks()
    {
        return $this->subtasks()->where('status', 'todo')->exists();
    }
}
