<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        $tasks = Task::where('user_id', $userId)->get();

        return response()->json([
            'success' => true,
            'data' => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:waiting,working,completed',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'nullable|string',
            'subtasks.*.description' => 'nullable|string',
            'subtasks.*.due_date' => 'nullable|date',
            'subtasks.*.status' => 'nullable|string|in:waiting,working,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $task = Task::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }

    public function show(Request $request, $id)
    {
        $userId = $request->input('user_id');
        $task = Task::where('user_id', $userId)->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|integer',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:waiting,working,completed',
            'subtasks' => 'nullable|array',
            'subtasks.*.id' => 'nullable|integer',
            'subtasks.*.title' => 'nullable|string',
            'subtasks.*.description' => 'nullable|string',
            'subtasks.*.due_date' => 'nullable|date',
            'subtasks.*.status' => 'nullable|string|in:waiting,working,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $userId = $request->input('user_id');
        $task = Task::where('user_id', $userId)->findOrFail($id);
        $task->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $userId = $request->input('user_id');
        $task = Task::where('user_id', $userId)->findOrFail($id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ]);
    }
}
