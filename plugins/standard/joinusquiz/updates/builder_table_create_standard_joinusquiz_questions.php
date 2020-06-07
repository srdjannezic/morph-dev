<?php namespace Standard\JoinUsQuiz\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateStandardJoinusquizQuestions extends Migration
{
    public function up()
    {
        Schema::create('standard_joinusquiz_questions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('question');
            $table->smallInteger('question_number');
            $table->text('options');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('standard_joinusquiz_questions');
    }
}
