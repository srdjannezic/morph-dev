<?php namespace Standard\Blocks\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStandardBlocks12 extends Migration
{
    public function up()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->string('video_url', 255)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('standard_blocks_', function($table)
        {
            $table->dropColumn('video_url');
        });
    }
}
