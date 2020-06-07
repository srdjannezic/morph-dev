<?php namespace Standard\Blocks\Models;

use Model;

/**
 * Model
 */
class StandardRegions extends Model
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
    public $table = 'standard_blocks_regions_';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];


    public $hasMany = [
        'block' => [
            'Standard\Blocks\Models\StandardBlock',
            'table' => 'standard_blocks_',
            'key'=>'region_id',
        ]
    ];
}
