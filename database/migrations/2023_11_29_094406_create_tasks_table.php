<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('task_id')->nullable()->constrained('tasks', 'id')->onDelete('cascade');
            $table->enum('status', ['todo', 'done']);
            $table->unsignedTinyInteger('priority')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->timestamp('completed_at')->nullable();

            $table->index(['status', 'priority', 'title', 'description']);
            $table->index(['created_at', 'completed_at', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
