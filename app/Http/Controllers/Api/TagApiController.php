<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagApiController extends Controller
{

    public function tasks($id)
    {
        $tag = Tag::find($id);
        $tasks = $tag->tasks()->with('author', 'category', 'images', 'videos', 'comments')->orderBy('id', 'desc')->paginate();
        return TaskResource::collection($tasks);
    }
}
