<?php namespace Standard\Blocks\Components;

use Redirect;
use BackendAuth;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Standard\Blocks\Models\StandardBlock as Block;
use RainLab\Blog\Models\Category as BlockCategory;

class Blocks extends ComponentBase
{

    /**
     * A collection of blocks to display
     * @var Collection
     */
    public $blocks;

    /**
     * If the post list should be filtered by a category, the model to use.
     * @var Model
     */
    public $category;

    public function componentDetails()
    {
        return [
            'name'        => 'Blocks List',
            'description' => 'Display a list of blocks'
        ];
    }

    public function defineProperties()
    {
        return [
            'categoryFilter' => [
                'title'       => 'rainlab.blog::lang.settings.posts_filter',
                'description' => 'rainlab.blog::lang.settings.posts_filter_description',
                'type'        => 'string',
                'default'     => ''
            ],
            'regionFilter' => [
                'title'       => 'Region',
                'type'        => 'string',
                'default'     => ''
            ],
        ];
    }


    public function onRun()
    {
        $this->blocks = $this->page['blocks'] = $this->listBlocks();     
        $this->page['category'] = $this->property('categoryFilter');
    }

    protected function listBlocks()
    {
        /*
         * List all the blocks, eager load their categories
         */
        $slug = $this->property('categoryFilter');
        $region = $this->property('regionFilter');
        //var_dump($slug);
        $blocks = new Block;
        
        if($slug && $region) {
            $blocks = $blocks->where('category_id',$slug)
                             ->where('region_id',$region)
                             ->get();
        }
        elseif($slug){
            $blocks = $blocks->where('category_id',$slug)
                             ->get();
        }
        elseif($region){
            $blocks = $blocks->where('region_id',$region)
                             ->get();
        }
        else{
            $blocks = $blocks->get();
        }

        // convert json to array
        foreach ($blocks as $block) {

            if(isset($block->attributes['images'])){
                $images = json_decode($block->attributes['images']);
                foreach ($images as $image) {
                    $is_video = $this->filterIsVideo($image->images);

                    if($is_video){
                        $block->attributes['video'] = $image->images;
                        if(isset($image->videotime)){
                            $block->attributes['videotime'] = $image->videotime;
                        }

                        if(isset($image->thumbnail)){
                            $block->attributes['thumbnail'] = $image->thumbnail;
                        }

                        if(isset($image->is_autoplay)){
                            $block->attributes['autoplay'] = $image->is_autoplay;
                        }

                        if(isset($image->image_title)){
                            $block->attributes['image_title'] = $image->image_title;
                        }
                        break;
                    }
                }
                $block->attributes['images'] = $images;
            }
        }
        return $blocks;
    }

    protected function loadCategory()
    {
        if (!$slug = $this->property('categoryFilter')) {
            return null;
        }

        //var_dump($slug);

        $category = BlockCategory::select('*')->where('name',$slug)->first();
        if(isset($category->attributes)){
            $category = $category->attributes;
        }
        return $category ? $category : null;
    }

    private function filterIsVideo($file){
        $video = false;
        $exploded = explode(".",$file);
        $ext = end($exploded);

        if(strtolower($ext) =="mp4")
        {
            $video = true;
        }
        elseif(strtolower($ext) =="avi"){
            $video = true;
        }  
        elseif(strtolower($ext) =="wmv"){
            $video = true;
        }   
        elseif(strtolower($ext) =="webm"){
            $video = true;
        }       
        elseif(strtolower($ext) =="3gp"){
            $video = true;
        }   
        elseif(strtolower($ext) =="ogg"){
            $video = true;
        }

        return $video;         
    }

}
