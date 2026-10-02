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
    Schema::create('exam_result_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('exam_result_id')->constrained()->cascadeOnDelete();
        $table->unsignedBigInteger('subject_id')->nullable();
        $table->string('subject_name')->nullable();
        $table->unsignedBigInteger('question_id')->nullable();
        $table->longText('question_text');
        $table->unsignedBigInteger('selected_answer_id')->nullable();
        $table->text('selected_text')->nullable();
        $table->unsignedBigInteger('correct_answer_id')->nullable();
        $table->text('correct_text')->nullable();
        $table->unsignedInteger('points')->default(0);
        $table->boolean('is_correct')->default(false);
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('exam_result_answers');
    }
};
