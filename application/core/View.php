<?php

namespace application\core;

 class View 
{
    public $path;// путь к например майн\индекс
    public $layout = 'default';
    public $title = 'Сара стажировка';
    public $route;
   /*  футер и хидер -  это шаблон
    все посередине - вид
    нет смысла создавать футер и хидер каждый раз 
    можно их просто подключать */
    //Нужно как-то получить наши роуты
    public function __construct($route)
    {
        $this->route = $route;
        //формируем путь к виду(лайот)
        $this->path = $route['controller'].'/'.$route['action'];
    
    }

  //render - собирает шаблон и вид
  //$vars - переменная которую хотим передать в наш вид
  public function render ($title,$vars = [],$totalPage)
  { 
    
    //debug($vars);
    extract($vars);

   
    
$path = 'application/views/'.$this->path.'.php';

    if(file_exists($path))
      {ob_start();
      require $path;
        $path1 = 'application/views/layouts/'.$this->layout.'.php';
      $content = ob_get_clean();
      require $path1;
      }else{

        echo ('Вид не найден:'. $path. '');
        echo "</br>";
      
      }

  }


  public static function errorCode($code){

    http_response_code($code);
    $path = 'application/views/errors/'.$code.'.php';
    if (file_exists($path)){
    require $path;}
    exit;
  }

  //redirect

  public static function redirect($url)
  {
    header('Location:'.$url);
    exit;
  }


}