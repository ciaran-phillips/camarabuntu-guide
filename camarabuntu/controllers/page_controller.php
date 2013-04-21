<?php
class PageController extends Controller{
    public $controllerName = 'page';
    public function __construct() {
        $this->model = new PageModel();
        $this->categoryModel = new CategoryModel();
    }
    public function index() {
        $this->render("views/index.html", array(
			'content' => 'views/' . $this->controllerName . '/index.html', 
				'content_variables' => array(
					'message' => 'yo'
				)
			)
		);
    }
    
    
    public function view($params) {
        
		$id = $_GET['id'];
		$page = $this->model->load(array('rowid' => $id));
		$this->render('views/index.html', array(
				'content' => 'views/' . $this->controllerName . '/view.html',
				'content_variables' => array(
					'page' => $page[0]
				)
			)
		);
        
    }    
}
