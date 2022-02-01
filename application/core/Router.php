<?php
namespace application\core;

use application\core\View;

class Router{


    protected $routes = array();
    protected $params = array();

    public function __construct(){
        
        $arr = require_once (ROOT.'/application/config/routes.php');
       

        foreach ($arr as $key=>$val)
        {
            $this->add($key,$val);//обращаемся ко всем методам класса и функциям через зис
        }
    }
    public function add($route,$params)//добавляем маршрут
    {
         $route = '#^'.$route.'$#';
         $this->routes[$route]= $params;
    }
    public function match()// проверяем маршрут
    {
        $url = trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');
//        debug($url);
        foreach ($this->routes as $route => $params)
        {
           
            if (preg_match($route,$url,$matches)){
                $this->params = $params;
                return true;
                //debug($matches);
            }


        }
        return false;

    }
    //запускаем маршрутизатор

    public function run()
    {
        if($this->match()){
          $path = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller';
          //Обратные слеши не забывай!!!!!111!!!

         //   debug( $path);

           if (class_exists($path)){

            $action = $this->params['action'].'Action';
            if (method_exists($path,$action)){

                $controller = new $path($this->params);
                $controller->$action();

            }else
            {
              View::errorCode(404);
            }

           }else{
               /* echo "This class is no:  ".$path; */

               View::errorCode(403);
           }
        }else{
            View::errorCode(403);
        };
        
    }
}