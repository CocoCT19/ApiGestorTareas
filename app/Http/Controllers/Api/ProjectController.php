<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::where('user_id', $request->user()->id)
            ->orderBy('priority', 'desc')
            ->orderBy('name', 'asc')
            ->with('tasks')
            ->get();

        return response()->json($projects, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|integer|min:1|max:3',
        ]);

        $project = Project::create([
            'user_id'     => $request->user()->id,
            'name'        => $request->name,
            'description' => $request->description,
            'priority'    => $request->priority,
            'is_archived' => $request->is_archived ?? false,
        ]);

        return response()->json($project, Response::HTTP_CREATED);
    }

    public function show(Project $project)
    {
        $project->load('tasks');
        return response()->json($project, Response::HTTP_OK);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name'        => 'string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'integer|min:1|max:3',
        ]);

        $project->update($request->only('name', 'description', 'priority', 'is_archived'));

        return response()->json($project, Response::HTTP_OK);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
