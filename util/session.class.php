<?php
namespace util;

class session {

   static function isUserLoggedIn(){
       session_start();
       if(isset($_SESSION['email_id'])&&!empty($_SESSION['email_id']))
           return true;
       else
           return false;

   }

}