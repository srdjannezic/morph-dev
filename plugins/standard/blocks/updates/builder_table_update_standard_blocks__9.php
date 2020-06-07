<?php namespace Standard\Blocks\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStandardBlocks9 extends Migration
{
    public function up()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->string('block_type', 55)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->dropColumn('block_type');
        });
    }
}
