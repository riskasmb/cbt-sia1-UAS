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
    Schema::create('exam_results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
        $table->string('subject_names')->nullable();
        $table->dateTime('submitted_at');
        $table->unsignedInteger('total_questions');
        $table->unsignedInteger('correct_count');
        $table->unsignedInteger('wrong_count');
        $table->decimal('score', 5, 2);
        $table->boolean('passed')->default(false);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
