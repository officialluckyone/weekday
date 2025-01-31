<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Relasi ke proyek
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('deadline');
            $table->enum('status', ['To-Do', 'In Progress', 'Done'])->default('To-Do');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
