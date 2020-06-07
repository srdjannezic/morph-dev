<?php namespace Standard\Blocks\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStandardBlocks extends Migration
{
    public function up()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->text('images')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->dropColumn('images');
        });
    }
}
