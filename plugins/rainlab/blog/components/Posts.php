<?php namespace RainLab\Blog\Components;

use Redirect;
use BackendAuth;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;
use RainLab\Blog\Models\Category as BlogCategory;

class Posts extends ComponentBase
{
    /**
     * A collection of posts to display
     * @var Collection
     */
    public $posts;

    /**
     * Parameter to use for the page number
     * @var string
     */
    public $pageParam;

    /**
     * If the post list should be filtered by a category, the model to use.
     * @var Model
     */
    public $category;

    /**
     * Message to display when there are no messages.
     * @var string
     */
    public $noPostsMessage;

    /**
     * Reference to the page name for linking to posts.
     * @var string
     */
    public $postPage;

    /**
     * Reference to the page name for linking to categories.
     * @var string
     */
    public $categoryPage;

    /**
     * If the post list should be ordered by another attribute.
     * @var string
     */
    public $sortOrder;

    public function componentDetails()
    {
        return [
            'name'        => 'rainlab.blog::lang.settings.posts_title',
            'description' => 'rainlab.blog::lang.settings.posts_description'
        ];
    }

    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title'       => 'rainlab.blog::lang.settings.posts_pagination',
                'description' => 'rainlab.blog::lang.settings.posts_pagination_description',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'categoryFilter' => [
                'title'       => 'rainlab.blog::lang.settings.posts_filter',
                'description' => 'rainlab.blog::lang.settings.posts_filter_description',
                'type'        => 'string',
                'default'     => ''
            ],
            'postsPerPage' => [
                'title'             => 'rainlab.blog::lang.settings.posts_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_per_page_validation',
                'default'           => '10',
            ],
            'noPostsMessage' => [
                'title'        => 'rainlab.blog::lang.settings.posts_no_posts',
                'description'  => 'rainlab.blog::lang.settings.posts_no_posts_description',
                'type'         => 'string',
                'default'      => 'No posts found',
                'showExternalParam' => false
            ],
            'relatedPosts' => [
                'title'       => 'Related Posts? ',
                'type'        => 'dropdown',
                'default'     => 'yes/no',
            ],
            'sortOrder' => [
                'title'       => 'rainlab.blog::lang.settings.posts_order',
                'description' => 'rainlab.blog::lang.settings.posts_order_description',
                'type'        => 'dropdown',
                'default'     => 'published_at desc'
            ],
            'categoryPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_category',
                'description' => 'rainlab.blog::lang.settings.posts_category_description',
                'type'        => 'dropdown',
                'default'     => 'blog/category',
                'group'       => 'Links',
            ],
            'postPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_post',
                'description' => 'rainlab.blog::lang.settings.posts_post_description',
                'type'        => 'dropdown',
                'default'     => 'blog/post',
                'group'       => 'Links',
            ],
            'exceptPost' => [
                'title'             => 'rainlab.blog::lang.settings.posts_except_post',
                'description'       => 'rainlab.blog::lang.settings.posts_except_post_description',
                'type'              => 'string',
                'validationPattern' => 'string',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_except_post_validation',
                'default'           => '',
                'group'             => 'Exceptions',
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getSortOrderOptions()
    {
        return BlogPost::$allowedSortingOptions;
    }

    public function onRun()
    {
        $this->prepareVars();

        $this->category = $this->page['category'] = $this->loadCategory();
        $this->posts = $this->page['posts'] = $this->listPosts();
        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->posts->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }
    }


    public function onFilterPosts()
    {
        $this->prepareVars();

        $this->category = $this->page['category'] = $this->loadCategory();
        //var_dump($_POST['type']);
        
        if(isset($_POST['type'])){
            $this->posts = $this->page['posts'] = $this->listPosts($_POST['type']);
        }
        else{
            $this->posts = $this->page['posts'] = $this->listPosts();
        }


        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->posts->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }
    }

    protected function prepareVars()
    {
        $this->page['is_ajax'] = false;
        if( isset($_POST['page']) ){
            $this->page['is_ajax'] = true;
        }
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noPostsMessage = $this->page['noPostsMessage'] = $this->property('noPostsMessage');

        /*
         * Page links
         */

        //var_dump($this->property('postPage'));
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
    }

    protected function listPosts($type=NULL)
    {
        $category = $this->category ? $this->category->id : null;

        /*
         * List all the posts, eager load their categories
         */
        $isPublished = !$this->checkEditor();
        $pageNumber = $this->property('pageNumber');
        $perPage = $this->property('postsPerPage');
        //var_dump($isPublished);
        if(isset($_POST['page'])){
            $pageNumber = (int)str_replace('?page=','',$_POST['page']);
        }
        
        $posts = BlogPost::with('categories')->listFrontEnd([
            'page'       => $pageNumber,
            'sort'       => $this->property('sortOrder'),
            'perPage'    => $this->property('postsPerPage'),
            'search'     => trim(input('search')),
            'category'   => $category,
            'exceptPost' => $this->property('exceptPost'),
        ]);

        if($type !== NULL){
            if($type == 'loadmore'){
                $perPage = $this->property('postsPerPage') * $pageNumber;
                //var_dump($perPage);
                $posts = BlogPost::with('categories')->listFrontEnd([
                    'page'       => 1,
                    'sort'       => $this->property('sortOrder'),
                    'perPage'    => $perPage,
                    'search'     => trim(input('search')),
                    'category'   => $category,
                    'exceptPost' => $this->property('exceptPost'),
                ]);

            }
        }

        //var_dump($post_args);

        $featured_post = BlogPost::where('is_featured',1)->get();

        $this->page['FeaturedPost'] = $featured_post[0];


        //dd($posts->getQueryLog());
        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        $posts->each(function($post) {
            if(isset($post->featured)){
            if($this->filterIsVideo($post->featured)){
                $post->video = $post->featured;
            }
            //var_dump($post->video);
            }

            if($this->postPage == '404') {
                $post->setUrl('single-post', $this->controller);
            }
            else{
                $post->setUrl($this->postPage, $this->controller);
            }
            
            //var_dump($post);
            $post->categories->each(function($category) {
                $category->setUrl($this->categoryPage, $this->controller);
            });
        });
        //var_dump($posts);
        return $posts;
    }

    protected function loadCategory()
    {
        if (!$slug = $this->property('categoryFilter')) {
            return null;
        }

        $category = new BlogCategory;

        $category = $category->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
            ? $category->transWhere('slug', $slug)
            : $category->where('slug', $slug);

        $category = $category->first();

        return $category ?: null;
    }

    protected function checkEditor()
    {
        $backendUser = BackendAuth::getUser();
        return $backendUser && $backendUser->hasAccess('rainlab.blog.access_posts');
    }

    protected function filterIsVideo($file){
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
