<?php namespace Standard\Blocks\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class BlockController extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    protected $regionId;

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Standard.Blocks', 'main-menu-item');
    }

	public function index($regionId = null)
	{

	    // Store the routed parameter to use later
	    $this->regionId = $regionId;
	    // Call the ListController behavior standard functionality
	    $this->asExtension('ListController')->index();
	}

	public function listExtendQuery($query)
	{
		//var_dump($query);
	   // Extend the list query to filter by the user id
	    if ($this->regionId)
	        $query->where('region_id', $this->regionId);
	}
}
