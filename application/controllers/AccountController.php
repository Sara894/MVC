<?php

namespace application\controllers;

use application\core\Controller;
use application\models\Account;

use application\validation\Validation;

class AccountController extends Controller
{

    /*  цвет фона у желающего авторизоваться или зарегестрир другой */

    public function before()
    {
        $this->view->layout = 'default';
    }


    public function loginAction()
    {

        $response = [
            'status' => false,
            'errors' => [
                'email' => "Неверный Email",

                'password' => "Пароль не совпал",
            ]];
        /*  Нам дают данные имя фамилия почта пароль в виде глобального массива request
         В массиве реквест остаются незаполненные поля id phone
         их надо убрать
         Затем мы просто делаем getOne и если getOne возвращает непустой объект выкидываем пользователя на страничку
          со списком всех пользователей
     */
        if (!empty($this->request))// если пользователь залил в формочки данные, то
        {

            if (Account::auth($this->request['email'], $this->request['password'])) {
                $response['status'] = true;
                $response['errors'] = 'Вы успешно авторизованы';
            }
            echo json_encode($response);
            die();
        }
        $this->view->render('Вход',$vars=[],1);
    }


    public function registerAction()
    {
        if (!empty($this->request))// если пользователь залил в формочки данные, то
        {
            $response = ['status' => false];
                $user = new Account();// создаем для него страничку в приложении
                $user->load($this->request);// и на эту страничку заливаем данные из формочки
                $validateResult = $user->validate($this->request);
                if (empty($validateResult)) {
                    $user->save();
                    $response = [
                        'status' => true,
                        'message' => "Вы зарегестрированны успешно"
                    ];
                } else {
                    $response['errors'] = $validateResult;
                }
                echo json_encode($response);
            die();

            }


        $this->view->render('Регистрация',$vars=[],1);
    }
}