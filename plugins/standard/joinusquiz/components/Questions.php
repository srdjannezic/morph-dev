<?php namespace Standard\JoinUsQuiz\Components;

use Redirect,Session,Request;
use BackendAuth;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Standard\JoinUsQuiz\Models\Questions as QuestionModel;

class Questions extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Questions List',
            'description' => 'Display a list of questions'
        ];
    }

    public function onRun()
    {
        $this->q = $this->page['questions'] = $this->loadQuestions(); 
    } 

    protected function loadQuestions(){
        $q = new QuestionModel;
        $last_q = $this->getSession();
        $results = $q->orderBy('question_number')->get();
        if($last_q >= 1){
            $results = $q->where('question_number','>',$last_q)->orderBy('question_number')->get();
        }

        $this->page['total_questions'] = $results->count();

        return $results;        
    }

    protected function setSession($q_num){
        Session::put($this->_getIp(), $q_num);
    }

    public function getSession(){
        return Session::get($this->_getIp());
    }

    protected function _getIP() {
        if ($this->property('anonymize_ip') == 'full') {
            $address = '(Not stored)';
        } else if ($this->property('anonymize_ip') == 'partial') {
            $address = BackendHelpers::anonymizeIPv4(Request::getClientIp());
        } else {
            $address = Request::getClientIp();
        }
        return $address;
    }

    /**
     * A collection of questions to display
     * @var Collection
     */
    public $q;

}
