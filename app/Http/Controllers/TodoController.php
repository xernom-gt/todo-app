<?php
namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Category;
use App\Models\Priority;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    // Helper untuk log history
    private function logHistory($todoId, $action, $desc) {
        History::create([
            'user_id' => Auth::id(),
            'todo_id' => $todoId,
            'action' => $action,
            'description' => $desc
        ]);
    }

    public function index() {
        $todos = Todo::where('user_id', Auth::id())
                     ->with(['category', 'priority'])
                     ->latest()->get();
        $categories = Category::where('user_id', Auth::id())->get();
        $priorities = Priority::all();
        $histories = History::where('user_id', Auth::id())->latest()->take(10)->get();

        return view('dashboard', compact('todos', 'categories', 'priorities', 'histories'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority_id' => 'required|exists:priorities,id',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();
        $todo = Todo::create($validated);

        $this->logHistory($todo->id, 'Created', "Tugas '{$todo->title}' dibuat.");

        return redirect()->route('dashboard')->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function update(Request $request, Todo $todo) {
        if ($todo->user_id !== Auth::id()) abort(403);

        $todo->update($request->all());
        $this->logHistory($todo->id, 'Updated', "Tugas '{$todo->title}' diperbarui.");

        return redirect()->route('dashboard')->with('success', 'Tugas diperbarui!');
    }

    public function toggleComplete(Todo $todo) {
        if ($todo->user_id !== Auth::id()) abort(403);

        $todo->is_completed = !$todo->is_completed;
        $todo->save();

        $status = $todo->is_completed ? 'Selesai' : 'Belum Selesai';
        $this->logHistory($todo->id, 'Status', "Tugas '{$todo->title}' ditandai {$status}.");

        return back();
    }

    public function destroy(Todo $todo) {
        if ($todo->user_id !== Auth::id()) abort(403);

        $title = $todo->title;
        $todo->delete();

        // Pass null to todo_id since it's deleted, but keep the record
        $this->logHistory(null, 'Deleted', "Tugas '{$title}' dihapus.");

        return back()->with('success', 'Tugas dihapus.');
    }
}