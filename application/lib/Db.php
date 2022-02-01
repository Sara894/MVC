<?php
namespace application\lib;

use PDO;
class Db
{
    public $db;
    public function __construct(){
        $config =  require ROOT.'application/config/db.php';
        // обратные слеши
      

        $this->db = new PDO  ('mysql:host='.$config['host'].';dbname='.$config['dbname'].'', $config['user'], $config['pass']);
    }

    public function query($sql,$params = []){


        //защита от sql инЪекции
        
        $stmt = $this->db->prepare($sql);
       if (!empty($params))// params передает один из дочерних контролеров 
       {
           foreach ($params as $key=>$val )
           {
               $stmt->bindValue(':'.$key ,  $val);
             // debug($stmt);

              /*  echo '<p>'.$key.'=>'.$val.'</p>';  */  
           }
       }

     /*   exit; */
       $stmt->execute();

       return $stmt;


       
    }

    public function row($sql, $params = []){
        $result = $this->query($sql, $params );

        return $result->fetchALL(PDO::FETCH_ASSOC);
    }

    public function column($sql, $params = []){
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }

 
}