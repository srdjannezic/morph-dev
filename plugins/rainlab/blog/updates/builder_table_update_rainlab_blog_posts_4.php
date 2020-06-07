<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRainlabBlogPosts4 extends Migration
{
    public function up()
    {
        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->string('is_autoplay')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->dropColumn('is_autoplay');
        });
    }
}
