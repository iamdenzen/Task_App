<?php

namespace App\Http\Controllers\Api;

use App\Models\Progress_list;
use App\Http\Resources\Progress_listResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Progress_listApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'progress_list'   => 'required',
            'id'   => 'required'
        ]);
        $progress_list = new Progress_list();
        $progress_list->remarks = $request->get('remarks');
        $progress_list->task_id =  $request->get('id');
        $progress_list->user_id = $request->user()->id;
        $progress_list->save();
        return new Progress_listResource($progress_list);
    }
}
