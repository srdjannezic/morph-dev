<?php namespace Standard\Blocks\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateStandardBlocks extends Migration
{
    public function up()
    {
        Schema::create('standard_blocks_', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 255);
            $table->text('title')->nullable();
            $table->text('body')->nullable();
            $table->text('subtitle')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('standard_blocks_');
    }
}
