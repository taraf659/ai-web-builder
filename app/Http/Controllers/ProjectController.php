<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        // For now, return all projects (in production, filter by authenticated user)
        $projects = Project::active()
            ->where('is_temporary', false)
            ->orWhere(function ($query) {
                $query->where('is_temporary', true)
                    ->where('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'projects' => $projects,
        ]);
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);

        return response()->json([
            'success' => true,
            'project' => $project,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'html_code' => 'nullable|string',
            'css_code' => 'nullable|string',
            'js_code' => 'nullable|string',
            'technology_stack' => 'nullable|array',
        ]);

        $project = Project::create([
            'user_id' => 1, // Temporary - should be auth()->id() in production
            'name' => $request->name,
            'description' => $request->description,
            'html_code' => $request->html_code,
            'css_code' => $request->css_code,
            'js_code' => $request->js_code,
            'technology_stack' => $request->technology_stack ?? [],
            'status' => 'active',
            'is_temporary' => false,
        ]);

        return response()->json([
            'success' => true,
            'project' => $project,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:active,completed,archived',
        ]);

        $project->update($request->only(['name', 'description', 'status']));

        return response()->json([
            'success' => true,
            'project' => $project,
        ]);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ]);
    }
}
