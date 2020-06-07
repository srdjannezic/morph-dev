<?php namespace Martin\Forms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinFormsRecords extends Migration
{
    public function up()
    {
        Schema::table('martin_forms_records', function($table)
        {
            $table->text('email')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martin_forms_records', function($table)
        {
            $table->dropColumn('email');
        });
    }
}
