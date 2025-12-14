<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_create_histories_table.php
public function up(): void
{
    Schema::create('histories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Jika Todo dihapus, log tetap ada (set null)
        $table->foreignId('todo_id')->nullable()->constrained()->onDelete('set null'); 
        $table->string('action'); // Created, Updated, Deleted, Completed
        $table->string('description');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
