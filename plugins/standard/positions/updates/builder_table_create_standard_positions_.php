<?php namespace Standard\Positions\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateStandardPositions extends Migration
{
    public function up()
    {
        Schema::create('standard_positions_', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('name');
            $table->text('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('standard_positions_');
    }
}
