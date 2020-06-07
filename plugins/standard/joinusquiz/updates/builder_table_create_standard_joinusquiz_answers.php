<?php namespace Standard\JoinUsQuiz\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateStandardJoinusquizAnswers extends Migration
{
    public function up()
    {
        Schema::create('standard_joinusquiz_answers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->text('answer');
            $table->integer('question_id');
            $table->integer('user_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('standard_joinusquiz_answers');
    }
}
