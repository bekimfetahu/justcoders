<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('answer_id')->constrained('answers');
            $table->foreignId('question_id')->constrained('questions');
            $table->foreignId('edited_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->nullable()->constrained('users');
            
            $table->integer('status')->comment('0 => deleted, 1 => active');     
            $table->boolean('accepted')->default(false);       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
