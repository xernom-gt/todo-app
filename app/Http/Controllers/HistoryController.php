<?php
namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function destroy(History $history) {
        if ($history->user_id !== Auth::id()) abort(403);
        $history->delete();
        return back()->with('success', 'Log riwayat dihapus.');
    }
}