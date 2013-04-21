<?php
class ApplicationController extends Controller{
    public $controllerName = 'application';
    public function __construct() {
        $this->model = new ApplicationModel();
        $this->levelModel = new LevelModel();
        $this->subjectModel = new SubjectModel();
        $this->appLevelModel = new ApplicationLevelModel();
        $this->appSubjectModel = new ApplicationSubjectModel();
    }
    

    public function browse() {
        $levelID = (isset($_GET['level']) ? $_GET['level'] : null);
        $appID = (isset($_GET['app']) ? $_GET['app'] : null);
        $subjectID = (isset($_GET['subject']) ? $_GET['subject'] : null);
        
        
        $levels = $this->levelModel->load(array());
        $contentVariables['levels'] = $levels;
        
        if ($levelID != null) {
            $contentVariables['selected_level'] = $levelID;
            $subjects = $this->subjectModel->load(array());                
            $applications = $this->model->load(array());
            $applicationLevels = $this->appLevelModel->load(array('level' => $levelID));
            $applicationSubjects = $this->appSubjectModel->load(array());
            
            $appsWithLevel = array();
            foreach($applicationLevels as $appLevel) {
                array_push($appsWithLevel, $appLevel['application']);
            }        
            $subjectsDisplay = array();
            $appsDisplay = array();
            
            foreach($subjects as $subject) {
                foreach($applicationSubjects as $appSubject) {
                    if ($subject['rowid'] == $appSubject['subject'] && in_array($appSubject['application'], $appsWithLevel)) {
                        
                        if (!in_array($subject, $subjectsDisplay))
                            array_push($subjectsDisplay, $subject);
                        
                        if(isset($subjectID) && $subject['rowid'] == $subjectID) {
                            array_push($appsDisplay, $appSubject['application']);
                        }
                    }
                    
                }
            }
            $contentVariables['subjects'] = $subjectsDisplay;
            
            $apps = array();
            if ($subjectID != null) {
                
                foreach ($applications as $app) {
                    if (in_array($app['rowid'], $appsDisplay))
                        array_push($apps, $app);
                }
            }
            $contentVariables['apps'] = $apps;
            
        }
        $this->render("views/index.html", array(
                'content' => 'views/' . $this->controllerName . '/browse.html', 
                    'content_variables' => $contentVariables
                )
            );
    }
    
    public function create() {
            
            Model::openDB();
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            }
            else {
                $id = null;
            }
            if (isset($_POST['name'])) {
            
                $this->model->setFields($id, $_POST);
                if ($id == null)
                    $id = $this->model->save();
                else 
                    $this->model->save();
                $this->model->setRelatedFields($id, $_POST);
                $this->model->saveRelated();
            }
            if (isset($_GET['id'])) {
                $currentFields = $this->model->load(array('rowid' => $_GET['id']));
                $fields = $currentFields[0];
                
                $currentLevels = $this->model->loadLevels($_GET['id']);
                $currentSubjects = $this->model->loadSubjects($_GET['id']);
                
                $contentVariables['current_levels'] = $currentLevels;
                $contentVariables['current_subjects'] = $currentSubjects;
            }
            else {
                $fields = $this->model->getFieldList();
                $currentLevels = array();
                $currentSubjects = array();
            }
            
            
            $contentVariables = array('fields' => $fields);
            $contentVariables['current_levels'] = $currentLevels;
            $contentVariables['current_subjects'] = $currentSubjects;
            // Load all education levels
            $levelModel = new LevelModel();
            $levels = $levelModel->load(array());
            $contentVariables['levels'] = $levels;
            
            // Load all subjects
            $subjectModel = new SubjectModel();
            $subjects = $subjectModel->load(array());
            $contentVariables['subjects'] = $subjects;
        
            
            $this->render("views/index.html", array(
                    'content' => 'views/'.$this->controllerName . '/create.html',
                    'content_variables' => $contentVariables 
                )
            );
            
            
        }
        
    
    
    
}
