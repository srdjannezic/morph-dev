<?php namespace Standard\Blocks\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateStandardBlocksRegions extends Migration
{
    public function up()
    {
        Schema::create('standard_blocks_regions_', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->text('name');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('standard_blocks_regions_');
    }
}
