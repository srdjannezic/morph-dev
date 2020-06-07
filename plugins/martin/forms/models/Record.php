<?php

    namespace Martin\Forms\Models;

    use Model;
    use System\Models\File;
    
    class Record extends Model {

        use \October\Rain\Database\Traits\SoftDelete;

        public $table = 'martin_forms_records';

        protected $dates = ['deleted_at'];

        public $attachMany = [
            'files' => ['System\Models\File', 'public' => false]
        ];

        public function getAttachedFiles(){
        	$file = new File;
        	$files = $file->where('record_id',$this->id)->get();
        	return $files;
        }

        public function getFormDataArrAttribute() {
            return (array) json_decode($this->form_data);
        }

        public function filterGroups() {
            return Record::orderBy('group')->groupBy('group')->lists('group', 'group');
        }

        public function getGroupsOptions() {
            return $this->filterGroups();
        }

    }

?>