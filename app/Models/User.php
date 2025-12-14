<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];

    public function categories() {
        return $this->hasMany(Category::class);
    }
    
    public function todos() {
        return $this->hasMany(Todo::class);
    }

    public function histories() {
        return $this->hasMany(History::class);
    }
}