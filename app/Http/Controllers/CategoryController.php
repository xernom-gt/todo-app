<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:50']);
        
        Category::create([
            'user_id' => Auth::id(),
            'name' => $request->name
        ]);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function destroy(Category $category) {
        if ($category->user_id !== Auth::id()) abort(403);
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}