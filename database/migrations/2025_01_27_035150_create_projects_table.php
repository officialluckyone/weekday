<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pic_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('begin')->nullable();
            $table->date('end')->nullable();
            $table->enum('status',['Ongoing','Decline','Done','Nothing'])->default('Ongoing');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('pic_id','foreign_pic_user_projects')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('foreign_pic_user_projects');
            $table->dropColumn('pic_id');
        });
        Schema::dropIfExists('projects');
    }
};
