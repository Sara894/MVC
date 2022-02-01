<?php

namespace application\models;

use application\core\Model;
use application\protect\protectPassword;
use application\core\Controller;

class Account extends Model
{

    public $id;
    public $email;
    public $name;
    public $surname;
    public $phone;
    public $address;
    public $password;

    //public $request = [];// то куда складвыем данные из HTML формы

    static $table = 'users';
    /* При регистрации и авторизации должна работать валидация.
    Пустых полей быть не может, email и телефон (11 цифр и перед ними знак +)
     проверяется на валидность, длина пароля не менее 8 символов,
      должны использоваться буквы обоих регистров, знаки препинания и цифры. */


    public static $rules = array(
        'name' => [
            [
                'type' => 'pattern',
                'rule' => "#[A-Z]{1}[a-z]+$#"
            ]
        ],
        'surname' => [
            [
                'type' => 'pattern',
                'rule' => "#[A-Z]{1}[a-z]+$#"
            ]
        ],
        'email' => [
            [
                'type' => 'pattern',
                'rule' => "#^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$#"
            ],
            [
                'type'=>'method',
                'rule'=>'checkEmail'
            ]
        ],
        'phone' => [
            [
                'type' => 'pattern',
                'rule' => "#^((\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$#"
            ],
            [
                'type' => 'method',
                'rule' => 'checkPhone'
            ]
        ],
        'password' => [
            [
                'type' => 'pattern',
                'rule' => "#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$#"
            ]
        ],
        'password_2' => [
            [
                'type' => 'pattern',
                'rule' => "#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$#"
            ],
            [
                'type' => 'method',
                'rule' => 'checkPassword'
            ]
        ]
    );

    public static $errorKey = array(
        'name' => [
            'pattern' => "Заполните имя правильно. Учтите,что имя должно начинаться с заглавной буквы и состоять только из букв"
        ],
        'surname' => [
            'pattern' => "Заполните фамилию верно.  Учтите,что имя должно начинаться с заглавной буквы и состоять только из букв"
        ],
        'email' => [
            'pattern' => "Не верный формат Email",
            'method'=>"Такой email уже есть"
        ],
        'phone' => [
            'pattern' => "Не верный номер телефона(формат +7(000)000-00-00)",
            'method' => "Такой телефон уже есть в базе данных"
        ],
        'password' => [
            'pattern' => "Пароль должен содержать:число, латинскую букву в верхнем и нижнем регистре,длинна не менее 8 символов"
        ],
        'password_2' => [
            'pattern' => "Пароль должен содержать:число, латинскую букву в верхнем и нижнем регистре,длинна не менее 8 символов",
            'method' => "Пароли не совпадают"
        ]
    );

    public function checkPassword($password)
    {
        return ($password == $this->password);
    }

    public function checkPhone($phone){
        return (empty(self::getOne(['phone'=>$phone]))) ? true : false;
    }


    static public function checkEmail($email)
    {
        return (empty(self::getOne(['email' => $email]))) ? true : false;
    }




    static public function auth($email, $password)
    {
        $user = new Account();
        $user = self::getOne(['email' => $email]);// чтобы при авторизации пользователь вводил еще и имя и фамилию?
        if ($user) {
            if (protectPassword::passwordCheck($password, $user->password)) {
                //создаем сессию
                Controller::createSession('email', $user->email);
                return true;
            } else {
                return false;
            }
        }
    }

    public function beforeSave()
    {
        //var_dump($this->password);
        if ($this->password) {// что проверяет эта строка?

            $this->password = protectPassword::passwordHash($this->password);// солим пароль и хешируем
        } else {
            /*    $account  = self::getOne(['id'=>$this->id]);//
               //debug($account);
               $this->password = $account->password;//почему красный
    */

        }
    }


}