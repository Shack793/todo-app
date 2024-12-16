<?php
namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Todo Management
 *
 * APIs for managing todos
 */
class TodoController extends Controller
{
    /**
     * List all todos
     * 
     * Get a list of todos with optional filtering, sorting, and searching capabilities.
     *
     * @queryParam status string Filter todos by status (not_started/in_progress/completed). Example: completed
     * @queryParam search string Search todos by title or details. Example: important task
     * @queryParam sort_by string Field to sort by (id/title/status/created_at). Default: created_at. Example: title
     * @queryParam sort_order string Sort direction (asc/desc). Default: desc. Example: asc
     * 
     * @response 200 {
     *    "data": [{
     *        "id": 1,
     *        "title": "Complete project documentation",
     *        "details": "Write comprehensive API documentation",
     *        "status": "in_progress",
     *        "created_at": "2023-12-15T00:00:00.000000Z",
     *        "updated_at": "2023-12-15T00:00:00.000000Z"
     *    }]
     * }
     */
    public function index(Request $request)
    {
        try {
            $query = Todo::query();

            // Filtering by status if provided
            if ($request->filled('status')) {
                $status = strtolower($request->status);
                if (in_array($status, ['not_started', 'in_progress', 'completed'])) {
                    $query->where('status', $status);
                }
            }

            // Search by title or details if search term is provided
            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where(DB::raw('LOWER(title)'), 'like', strtolower($searchTerm))
                      ->orWhere(DB::raw('LOWER(details)'), 'like', strtolower($searchTerm));
                });
            }

            // Validate and apply sorting
            $allowedSortFields = ['id', 'title', 'status', 'created_at'];
            $sortBy = in_array($request->sort_by, $allowedSortFields) ? $request->sort_by : 'created_at';
            $sortOrder = strtolower($request->sort_order) === 'asc' ? 'asc' : 'desc';
            
            $query->orderBy($sortBy, $sortOrder);

            // Get all results without pagination
            $todos = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => $todos,
                'count' => $todos->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching todos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new todo
     *
     * @bodyParam title string required The title of the todo. Example: Review pull request
     * @bodyParam details string optional The detailed description of the todo. Example: Review and merge the new feature PR
     * @bodyParam status string required The status of the todo (not_started/in_progress/completed). Example: not_started
     *
     * @response 201 {
     *    "id": 1,
     *    "title": "Review pull request",
     *    "details": "Review and merge the new feature PR",
     *    "status": "not_started",
     *    "created_at": "2023-12-15T00:00:00.000000Z",
     *    "updated_at": "2023-12-15T00:00:00.000000Z"
     * }
     * 
     * @response 422 {
     *    "message": "The given data was invalid.",
     *    "errors": {
     *        "title": ["The title field is required."],
     *        "status": ["The status field must be one of: not_started, in_progress, completed."]
     *    }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'nullable|string',
            'status' => 'required|in:not_started,in_progress,completed',
        ]);

        $todo = Todo::create($validated);

        return response()->json($todo, 201);
    }

    /**
     * Update a todo
     *
     * @urlParam id integer required The ID of the todo. Example: 1
     * @bodyParam title string optional The title of the todo. Example: Updated task title
     * @bodyParam details string optional The detailed description of the todo. Example: Updated task description
     * @bodyParam status string optional The status of the todo (not_started/in_progress/completed). Example: completed
     *
     * @response 200 {
     *    "id": 1,
     *    "title": "Updated task title",
     *    "details": "Updated task description",
     *    "status": "completed",
     *    "created_at": "2023-12-15T00:00:00.000000Z",
     *    "updated_at": "2023-12-15T00:00:00.000000Z"
     * }
     * 
     * @response 404 {
     *    "message": "Todo not found"
     * }
     */
    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'details' => 'nullable|string',
            'status' => 'sometimes|required|in:not_started,in_progress,completed',
        ]);

        $todo->update($validated);

        return response()->json($todo);
    }

    /**
     * Delete a todo
     *
     * @urlParam id integer required The ID of the todo to delete. Example: 1
     *
     * @response 200 {
     *    "message": "Todo deleted successfully"
     * }
     * 
     * @response 404 {
     *    "message": "Todo not found"
     * }
     */
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully']);
    }
}
