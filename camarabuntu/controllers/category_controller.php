<?php
class CategoryController extends Controller{
    public $controllerName = 'category';
    public function __construct() {
        $this->model = new CategoryModel();
        $this->pageModel = new PageModel();
    }
    

    public function index() {
        
        $categories = $this->model->load(array());
        $pages = $this->pageModel->load(array());
        
        $this->render("views/index.html", array(
                'content' => 'views/' . $this->controllerName . '/index.html', 
                    'content_variables' => array(
                        'categories' => $categories,
                        'pages' => $pages
                    )
                )
            );
    }
    
    
}
