<?php

namespace application\core;

class Controller
//обЪекты асбстрактного класса создать нельзя
{
    public $route;
    public $request = [];//сюда пользователи отправляют свои данные при регистрации
    //чтобы передать маршруты в вид, создаем свойство вид
    public $view;

    public function __construct($route)
    {   //debug($_SESSION);// пустая
        /*  $this->user = new Account();
            $this->user->load(Account::getOne(['email' => $_SESSION['email']])); */

        //debug($_REQUEST);
        foreach ($_REQUEST as $key => $value) {// разбираем $_REQUEST на ассоциативный массив name=>ANN

            $this->request[$key] = htmlspecialchars($value);// при этом специальные символы обрабатываем
        }
        $this->route = $route;
        $this->view = new View($route);
        //debug($this->route);
        $this->model = $this->loadModel($route['controller']);// $route у нас ассоциативный массив где "контроллер " это ключ
        //все маршруты лежат в папке config/route
        //$this->AccountController->before();// почему она неопределенна
    }

    // загружаем модель
    public function loadModel($name)
    {
        $path = 'application\models\\' . ucfirst($name);
        if (class_exists($path)) {
            return new $path;
        }
    }

    public static function check($key)
    {
        return isset($_SESSION[$key]) ? true : false;
    }

    public static function createSession($key, $session)
    {

        $_SESSION[$key] = $session;
    }
}

?>