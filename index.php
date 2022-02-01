<?php



use application\core\Router;
//use application\lib\Db;
//session_start();
define ('ROOT',$_SERVER['DOCUMENT_ROOT']);

require_once(ROOT.'/application/lib/dev.php');//исправила
//include('application\core\Router.php');

spl_autoload_register(function($class){
    $path = str_replace('\\','/',$class.'.php');
    if (file_exists($path)){
        require $path;
    }
    //debug($path);

   //echo "<p>.$class.</p>";
}); 
session_start();
$router = new Router;// почему require не работает когда я namespace использую в рутере???
//$db = new Db;
$router->run();
