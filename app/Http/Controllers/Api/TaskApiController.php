<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['author', 'category', 'tags', 'images', 'videos', 'comments'])->paginate();
        return TaskResource::collection($tasks);
    }

    public function show($id)
    {
        $task = Task::with([
            'author', 'category', 'tags', 'images', 'videos', 'comments' => function ($query) {
                $query->with(['author']);
            }
        ])->find($id);
        return new TaskResource($task);
    }

    public function comments($id)
    {
        $task = Task::find($id);
        $comments = $task->comments->all();
        return CommentResource::collection($comments);
    }
}
