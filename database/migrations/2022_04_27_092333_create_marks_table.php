<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->comment("id of student from students table");
            $table->unsignedBigInteger('evaluator_id')->comment("id of evaluated teacher from teachers table");
            $table->string('term',15);
            $table->integer('maths');
            $table->integer('science');
            $table->integer('history');
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('evaluator_id')->references('id')->on('teachers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marks');
    }
}
