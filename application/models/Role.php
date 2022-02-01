<?php
namespace application\modelss;

use application\core\Model;

class Role extends Model{

    public $role;
    public $right;


  public function getRights($role){

      switch ($role){

          case 'admin':
              $right = 'all';
              break;

          case 'content-manager':
              $right = 'content';
              break;

          case 'user':
              $right = 'his_profile';
              break;

          default :
              $right = 'mimokrokodil';
      }
  }








}
