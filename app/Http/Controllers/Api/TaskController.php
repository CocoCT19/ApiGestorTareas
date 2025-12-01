<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date'
        ]);

        $task = Task::create($request->only(['project_id','title','description','due_date','is_completed']));
        return response()->json($task, Response::HTTP_CREATED);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->only(['title','description','due_date','is_completed']));
        return response()->json($task, Response::HTTP_OK);
    }

    public function complete($id)
    {
        $task = Task::findOrFail($id);
        $task->update(['is_completed' => true]);
        return response()->json($task, Response::HTTP_OK);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
