<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRainlabBlogPosts extends Migration
{
    public function up()
    {
        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->string('featured', 255);
        });
    }
    
    public function down()
    {
        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->dropColumn('featured');
        });
    }
}
