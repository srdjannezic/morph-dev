<?php namespace Standard\Blocks\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStandardBlocks13 extends Migration
{
    public function up()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->string('videotime', 255);
        });
    }
    
    public function down()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->dropColumn('videotime');
        });
    }
}
