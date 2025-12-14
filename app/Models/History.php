<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = ['user_id', 'todo_id', 'action', 'description'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function todo() {
        return $this->belongsTo(Todo::class);
    }
}
