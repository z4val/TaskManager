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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_list_id')->nullable(false);
            $table->string('name');
            $table->text('description');
            $table->date('limit_date')->nullable()->default(null);
            $table->boolean('is_complete')->default(false);
            $table->timestamps();

            $table->foreign('task_list_id')->references('id')->on('task_lists');
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
