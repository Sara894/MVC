<?php

namespace application\controllers;
use application\core\Model;
use application\core\Controller;
use application\models\Account;

class RoleController extends Controller{


    public function roleAction(){

        if (parent::check('email')){// тут мы проверяем зареган ли человек

            if ((Model::getOne($this->request['email'],$role = true))==1) // это администратор
                {
                    $role = new Role('admin');
                }

                }


}
}