<?php namespace Standard\Blocks\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStandardBlocks8 extends Migration
{
    public function up()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->dropColumn('repeater');
        });
    }
    
    public function down()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->text('repeater')->nullable();
        });
    }
}
