<?php
    class ApplicationModel extends Model {
        public $tableName = "applications";
        protected $extraFields = array('content' => '', 
                                'image_url' => '',
                                'screenshot_url' => '',
                                'executable' => '',
                                'description' => '');
                                
        public function loadSubjects($id) {
            $appSubjectModel = new ApplicationSubjectModel();
            $subjects = $appSubjectModel->load(array('application' => $id));
            return $subjects;
        }
        
        public function loadLevels($id) {
            $appLevelModel = new ApplicationLevelModel();
            $levels = $appLevelModel->load(array('application' => $id));
            return $levels;
        }
        
        public function setRelatedFields($id = null, $fields = array()) {
            
            $this->appLevelModels = array();
            $this->appSubjectModels = array();
            if (!isset($fields['levels'])) {
                $fields['levels'] = array();
            }
            $appLevelModel = new ApplicationLevelModel();
            
            $appLevelModel->clearApplication($id);
            foreach($fields['levels'] as $level) {
                $appLevelModel = new ApplicationLevelModel();
                
                $appLevelModel->setFields(null, array(
                        'level' => $level,
                        'application' => $id
                    ));
                
                array_push($this->appLevelModels, $appLevelModel);
                
            
            }
            if (!isset($fields['subjects'])) {
                $fields['subjects'] = array();
            }
            $appSubjectModel = new ApplicationSubjectModel();
            $appSubjectModel->clearApplication($id);
            foreach($fields['subjects'] as $subject) {
                $appSubjectModel = new ApplicationSubjectModel();
                $appSubjectModel->setFields(null, array(
                            'subject' => $subject,
                            'application' => $id
                        ));
                
                array_push($this->appSubjectModels, $appSubjectModel);
            }
        
        }
        
        public function saveRelated() {
            
            foreach ($this->appSubjectModels as $ASM) {
                $ASM->save();
            }
            foreach($this->appLevelModels as $ALM) {
                $ALM->save();
            }
        }
    }
    
