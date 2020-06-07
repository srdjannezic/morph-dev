<?php namespace Standard\Blocks\Models;

use Model;

/**
 * Model
 */
class StandardBlock extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */

    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'standard_blocks_';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $jsonable = ['images'];



    public $belongsTo = [
        'categories' => [
            'RainLab\Blog\Models\Category',
            'table' => 'rainlab_blog_categories',
            'key'=>'category_id',
            'order' => 'name',
        ],
        'region' => [
            'Standard\Blocks\Models\StandardRegions',
            'table' => 'standard_blocks_regions_',
            'key'=>'region_id',
            'order' => 'name'
        ]
    ];

}
