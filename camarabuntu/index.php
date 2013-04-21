<?php

include('models/model.php');
include('controllers/controller.php');

if (isset($_GET['c']))
    $controller = $_GET['c'];
else
    $controller = 'category';

$controllerClass = $controller . "Controller";

if (isset($_GET['a']))
    $action = $_GET['a'];
else 
    $action = 'index';
    
switch ($controller) {
    case 'page':
        include('controllers/page_controller.php');
        include('models/page_model.php');
        include('models/category_model.php');
        break;
    case 'subject':
        include('controllers/subject_controller.php');
        include('models/subject_model.php');
        break;
    case 'level':
        include('controllers/level_controller.php');
        include('models/level_model.php');
        break;
    case 'category':
        include('controllers/category_controller.php');
        include('models/category_model.php');
        include('models/page_model.php');
        break;
    case 'application':
        include('controllers/application_controller.php');
        include('models/application_model.php');
        include('models/level_model.php');
        include('models/subject_model.php');
        include('models/application_subject_model.php');
        include('models/application_level_model.php');
        break;
}

if (!class_exists(ucfirst($controllerClass)))
    throw new InvalidArgumentException("Controller does not exist");
else
    $r = new ReflectionClass($controllerClass);
    if (!$r->hasMethod($action))
        throw new InvalidArgumentException("Action does not exist");
    
$params = $_GET;

if (isset($_GET['export']) && $_GET['export'] == true) {
    export_site();
    
}

call_user_func_array(array(new $controllerClass, $action), $params);



function export_site() {
    $output = array();
    $code = 0;
    $command = "wget -q --mirror http://localhost\:8080/index.php && mv -f localhost\:8080 static/ ";
    $command .= " && tar -cf site.tar static  && rm -R static/localhost\:8080";
    $out = system($command, $out);
    $file = file_get_contents("site.tar");
    $out2 = system("rm site.tar", $out);
    header("Content-type: application/tar");    
    header('Content-Disposition: attachment; filename="downloaded.tar"');
    echo $file;
    
}
