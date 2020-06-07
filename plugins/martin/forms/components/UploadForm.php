<?php

    namespace Martin\Forms\Components;

    use AjaxException, Lang, Redirect, Request, Session, Validator;
    use October\Rain\Filesystem\Definitions;
    use Martin\Forms\Classes\MagicForm;
    use Martin\Forms\Models\Record;
    use Standard\JoinUsQuiz\Models\Questions;

    class UploadForm extends MagicForm {

        use \Martin\Forms\Traits\FileUploader;

        public function componentDetails() {
            return [
                'name'        => 'martin.forms::lang.components.upload_form.name',
                'description' => 'martin.forms::lang.components.upload_form.description',
            ];
        }


        public function init() {
            $this->fileTypes       = $this->processFileTypes(true);
            $this->maxSize         = $this->property('maxSize');
            $this->placeholderText = $this->property('placeholderText');
            $this->removeText      = $this->property('removeText');
            $this->setProperty('deferredBinding', 1);
            //$this->page['custom_files'] = Record::getAttachedFiles();
            $this->bindModel('files', new Record);
        }

        public function onRun() {
            $this->page['isTestFinished'] = $this->isTestFinished();
            parent::onRun();
            $this->addCss('assets/css/uploader.css');
            $this->addJs('assets/vendor/dropzone/dropzone.js');
            $this->addJs('assets/js/uploader.js');
            $this->isMulti = $this->property('uploader_multi');
            if($result = $this->checkUploadAction()) { 
                return $result; }
        }

        public function defineProperties() {
            $local = [
                'mail_uploads' => [
                    'title'             => 'martin.forms::lang.components.shared.mail_uploads.title',
                    'description'       => 'martin.forms::lang.components.shared.mail_uploads.description',
                    'type'              => 'checkbox',
                    'default'           => false,
                    'group'             => 'martin.forms::lang.components.shared.group_mail',
                    'showExternalParam' => false
                ],
                'uploader_enable' => [
                    'title'             => 'martin.forms::lang.components.shared.uploader_enable.title',
                    'description'       => 'martin.forms::lang.components.shared.uploader_enable.description',
                    'default'           => false,
                    'type'              => 'checkbox',
                    'group'             => 'martin.forms::lang.components.shared.group_uploader',
                    'showExternalParam' => false,
                ],
                'uploader_multi' => [
                    'title'             => 'martin.forms::lang.components.shared.uploader_multi.title',
                    'description'       => 'martin.forms::lang.components.shared.uploader_multi.description',
                    'default'           => true,
                    'type'              => 'checkbox',
                    'group'             => 'martin.forms::lang.components.shared.group_uploader',
                    'showExternalParam' => false,
                ],
                'placeholderText' => [
                    'title'             => 'martin.forms::lang.components.shared.uploader_pholder.title',
                    'description'       => 'martin.forms::lang.components.shared.uploader_pholder.description',
                    'default'           => Lang::get('martin.forms::lang.components.shared.uploader_pholder.default'),
                    'type'              => 'string',
                    'group'             => 'martin.forms::lang.components.shared.group_uploader',
                    'showExternalParam' => false,
                ],
                'removeText' => [
                    'title'             => 'martin.forms::lang.components.shared.uploader_remFile.title',
                    'description'       => 'martin.forms::lang.components.shared.uploader_remFile.description',
                    'default'           => Lang::get('martin.forms::lang.components.shared.uploader_remFile.default'),
                    'type'              => 'string',
                    'group'             => 'martin.forms::lang.components.shared.group_uploader',
                    'showExternalParam' => false,
                ],
                'maxSize' => [
                    'title'             => 'martin.forms::lang.components.shared.uploader_maxsize.title',
                    'description'       => 'martin.forms::lang.components.shared.uploader_maxsize.description',
                    'default'           => '5',
                    'type'              => 'string',
                    'group'             => 'martin.forms::lang.components.shared.group_uploader',
                    'showExternalParam' => false,
                ],
                'fileTypes' => [
                    'title'             => 'martin.forms::lang.components.shared.uploader_types.title',
                    'description'       => 'martin.forms::lang.components.shared.uploader_types.description',
                    'default'           => Definitions::get('defaultExtensions'),
                    'type'              => 'stringList',
                    'group'             => 'martin.forms::lang.components.shared.group_uploader',
                    'showExternalParam' => false,
                ],
            ];
            return array_merge(parent::defineProperties(), $local);
        }

        protected function isTestFinished(){
            $state = false;
            $current = $this->getSession();
            $q = Questions::orderBy('question_number','desc')->take(1)->get();
            $lastq = $q[0]->question_number;
            //var_dump($lastq);
            if($current == $lastq) $state = true;
            return $state;            
        }

        public function onQuestionChange(){
            /*
            if(isset($_POST['q_num']) ){
                //$this->setSession(1);
                $last_question = $this->getSession();
                $current_question = $_POST['q_num'];
                //var_dump($current_question);
                //var_dump($this->getSession());
                if($last_question < $current_question || $last_question == NULL){               
                    $this->setSession($current_question); 
                    $last_question = $this->getSession();
                    //var_dump($last_question);
                }

                //if($current_question > $last_question){
                    
                //}
            }*/
            //var_dump($this->getSession());
        }

        protected function setSession($q_num){
            $ip = $this->_getIp();
            $_SESSION[$ip] = $q_num;
        }

        public function getSession(){
            $ip = $this->_getIp();
            //var_dump($ip);
            if(isset($_SESSION[$ip])){
                return $_SESSION[$ip];
            }
            else{
                return 1;
            }
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

    }

?>