<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function add(Request $request)
    {
        $todo = Todo::forceCreate(['name'=>$request->name]);
        return $todo;
    }
    public function get(Request $request)
    {
        if ($request->type === 'all') {
            $todo = Todo::orderBy('created_at','desc')->get();
        } elseif ($request->type === 'completed') {
            $todo = Todo::where('is_complete',true)->orderBy('created_at','desc')->get();
        } else {
            $todo = Todo::where('is_complete',false)->orderBy('created_at','desc')->get();
        }

        $active = Todo::where('is_complete',false)->count();
        $hasCompleted = Todo::where('is_complete',true)->count();
        $total = Todo::count();
        $ret = [
            'active_now' => $active,
            'has_completed' => $hasCompleted,
            'todo' => $todo,
            'total' => $total
        ];
        return response()->json($ret);
    }

    public function todoAction(Request $request) {
        if ($request->type === 'complete') {
            Todo::where('id',$request->id)
                ->update(['is_complete' => true]);
        } else {
            Todo::where('id',$request->id)
                ->update(['is_complete' => false]);
        }
    }

    public function update(Request $request)
    {
        Todo::where('id',$request->id)
            ->update(['name'=>$request->name]);
    }

    public function delete(Request $request)
    {
        if ($request->type === 'single') {
            Todo::where('id',$request->id)->delete();
        } else {
            Todo::where('is_complete',true)->delete();
        }
    }
}
