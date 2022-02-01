<?php

namespace application\models;

use application\core\Model;

use application\lib\Db;

class Main extends Model
{


    static $table = 'users';
    public function getUsers(){
        $limit = 10;
        $offset = '';

        $result = $this->db->row("SELECT * FROM users LIMIT 0,5 ");
       // var_dump($result);//так не работает если просто передаю резалт в вид
        $vars=$result;// а так работает при передаче варс в вид
        return $vars;
    }
}