<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('tasks')->get();
        return response()->json($projects, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required|string|max:255']);
        $project = Project::create($request->only(['name','description','is_archived']));
        return response()->json($project, Response::HTTP_CREATED);
    }

    public function show(Project $project)
    {
        $project->load('tasks');
        return response()->json($project, Response::HTTP_OK);
    }

    public function update(Request $request, Project $project)
    {
        $project->update($request->only(['name','description','is_archived']));
        return response()->json($project, Response::HTTP_OK);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
