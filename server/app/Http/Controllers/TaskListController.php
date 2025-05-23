<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskListController extends Controller
{
    public function index()
    {
        $data = auth('api')->user()->taskLists()->with('tasks')->get();
        
        if($data->isEmpty())
        {
            return response()->json(["message" => "No TaskLists found"], 200);
        }

        return response()->json([
            'task_lists' =>  $data
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:50',
            'color' => 'string|max:7'
        ]);

        if($validated->fails())
        {
            return response()->json(["message" => "Request error"], 400);
        }

        $user_id = auth('api')->user()->id;
        // return response()->json(["user_id" => $user_id], 400);

        $task_list = TaskList::create([
            'user_id' => $user_id,
            'name' => $request->name,
            'color' => $request->color
        ]);

        if(!$task_list)
        {
            return response()->json(["message" => "Server error"], 500);
        }

        return response()->json([
            "message" => "TaskList created successfully",
            "task_list" => $task_list
        ], 201);
    }

    public function destroy($id)
    {
        $task_list = TaskList::find($id);
        
        if(!$task_list)
        {
            return response()->json(["message" => "TaskList not found"], 404);
        }

        $task_list->delete();

        return response()->json(["message" => "TaskList deleted successfully"], 200);
    }
}
