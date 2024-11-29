<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id') // Foreign key to users table
                  ->constrained('users')
                  ->onDelete('cascade'); // Deletes comment if user is deleted
            $table->foreignId('post_id') // Foreign key to posts table
                  ->constrained('posts')
                  ->onDelete('cascade'); // Deletes comment if post is deleted
            $table->text('content'); // Comment content
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
