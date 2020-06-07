<?php namespace Standard\Blocks;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    	return ['Standard\Blocks\Components\Blocks' => 'xblocks'];
    }

    public function registerSettings()
    {
    }
}
