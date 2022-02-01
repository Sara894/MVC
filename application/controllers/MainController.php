<?php
namespace application\controllers;
use application\core\View;
use application\core\Controller;
use application\models\Account;
class MainController extends Controller
{ //на главной странице выводятся все зарегестрированные пользователи
    public function indexAction()
    {
       // debug($_SESSION); //reply 'email' => string 't48@gmail.com'
        if (parent::check('email'))
        {$limit = 3;
        $page = (isset($this->request['page'])) ? (int)$this->request['page'] : 1;
       // debug($page);
        $data = Account::getPagination($limit,$page);
       // debug($date);
        $totalPage = $data['totalPage'];

        $vars = ["users"=>Account::getList([], [$limit, $page])];
        //var_dump( $vars);
        $this->view->render('Main',$vars,$totalPage);

        }
        else{
            $this->view->redirect('account/register');
            echo "Вам надо зарегестрироваться сначала";
        }

    }
}