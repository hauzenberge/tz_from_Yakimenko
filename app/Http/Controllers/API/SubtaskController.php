<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Subtask;
use Illuminate\Support\Facades\Validator;

class SubtaskController extends Controller
{
    public function index(Request $request)
    {
        $subtasks = Subtask::query()
            ->when($request->has('task_id'), function ($query) use ($request) {
                $query->where('task_id', $request->query('task_id'));
            })
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->query('status'));
            })
            ->get();

        return response()->json($subtasks);
    }

    public function show(Request $request, $id)
    {
        $subtask = Subtask::query()
            ->when($request->has('task_id'), function ($query) use ($request) {
                $query->where('task_id', $request->query('task_id'));
            })
            ->find($id);

        if (!$subtask) {
            return response()->json(['message' => 'Subtask not found'], 404);
        }

        return response()->json($subtask);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:waiting,working,completed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $subtask = Subtask::create([
            'task_id' => $request->input('task_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
            'status' => $request->input('status') ?? 'waiting',
        ]);

        return response()->json($subtask, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:waiting,working,completed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $subtask = Subtask::find($id);

        if (!$subtask) {
            return response()->json(['message' => 'Subtask not found'], 404);
        }

        $subtask->title = $request->input('title', $subtask->title);
        $subtask->description = $request->input('description', $subtask->description);
        $subtask->due_date = $request->input('due_date', $subtask->due_date);
        $subtask->status = $request->input('status', $subtask->status);

        $subtask->save();

        return response()->json($subtask);
    }

    public function destroy(Request $request, $id)
    {
        $subtask = Subtask::where('id', $id)->where('task_id', $request->input('task_id'))->firstOrFail();

        $subtask->delete();

        return response()->json(['message' => 'Subtask deleted successfully']);
    }
}
