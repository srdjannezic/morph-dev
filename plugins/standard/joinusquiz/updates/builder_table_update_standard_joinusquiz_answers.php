<?php namespace Standard\JoinUsQuiz\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStandardJoinusquizAnswers extends Migration
{
    public function up()
    {
        Schema::table('standard_joinusquiz_answers', function($table)
        {
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->bigIncrements('id')->unsigned(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('standard_joinusquiz_answers', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->bigIncrements('id')->unsigned()->change();
        });
    }
}
