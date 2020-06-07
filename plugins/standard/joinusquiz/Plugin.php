<?php namespace Standard\JoinUsQuiz;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    	return ['Standard\JoinUsQuiz\Components\Questions' => 'Questions'];
    }

    public function registerSettings()
    {
    }
}
