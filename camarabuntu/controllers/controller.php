<?php
    class Controller {
        protected $model = null;
        protected $template = "default.html";
        
        public function __construct() {
            
        }
        
        protected function render($view, $params) {
            foreach($params['content_variables'] as $index => $value) {
                $$index = $value;
            }
            $className = get_class($this);
            $viewPath = $view;
            
            if (file_exists($viewPath))
                include($viewPath);
            else
                echo "View file could not be found at $viewPath";
        }
        
        
        public function list_all() {
            Model::openDB();
            $modelList = $this->model->load(array());
            $this->render("views/dashboard.html", array(
                        'content' => 'views/' . $this->controllerName . '/list.html',
                        'content_variables' => array(
                            'models' => $modelList
                        )
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
                $this->model->save();
            }
            if (isset($_GET['id'])) {
                $currentFields = $this->model->load(array('rowid' => $_GET['id']));
                $fields = $currentFields[0];
                
            }
            else {
                $fields = $this->model->getFieldList();
            }
            $contentVariables = array('fields' => $fields);
            
            if (array_key_exists('category',$fields)) {
                $categoryModel = new CategoryModel();
                $categories = $categoryModel->load(array());
                
                $contentVariables['categories'] = $categories;
            }
            if (array_key_exists('level',$fields)) {
                $levelModel = new LevelModel();
                $levels = $levelModel->load(array());
                
                $contentVariables['levels'] = $levels;
            }
            if (array_key_exists('subject',$fields)) {
                $subjectModel = new SubjectModel();
                $subjects = $subjectModel->load(array());
                
                $contentVariables['subjects'] = $subjects;
            }
            
            $this->render("views/index.html", array(
                    'content' => 'views/'.$this->controllerName . '/create.html',
                    'content_variables' => $contentVariables 
                )
            );
            
            
        }
        
        
        public function delete($params) {
            $id = $_POST['id'];
            
            $success = $this->model->delete($id);
            
            if ($success)
                echo 'Successfully deleted';
            else 
                echo 'Operation failed';
        }
        
        public function view($id) {
            $controller = $this->controllerName;
            $contentVariables = array();
            $model = $this->model->load(array('rowid' => $_GET['id']));
            $contentVariables[$this->controllerName] = $model[0];
            $this->render("views/index.html", array(
                    'content' => 'views/'.$this->controllerName . '/view.html',
                    'content_variables' => $contentVariables 
                )
            );
        }
    }
