<?php namespace Standard\Blocks\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStandardBlocks7 extends Migration
{
    public function up()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->integer('category_id')->nullable();
            $table->dropColumn('category');
        });
    }
    
    public function down()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->dropColumn('category_id');
            $table->string('category', 255)->nullable();
        });
    }
}
