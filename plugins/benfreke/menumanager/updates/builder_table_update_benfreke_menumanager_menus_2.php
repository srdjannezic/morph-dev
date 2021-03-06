<?php namespace BenFreke\MenuManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBenfrekeMenumanagerMenus2 extends Migration
{
    public function up()
    {
        Schema::table('benfreke_menumanager_menus', function($table)
        {
            $table->string('type', 10)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('benfreke_menumanager_menus', function($table)
        {
            $table->dropColumn('type');
        });
    }
}
