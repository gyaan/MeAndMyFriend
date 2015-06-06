<?php
namespace model;
use model\myModel;
class users extends myModel {

    protected static  $tableName='users';

    static function getFriendsListOfAUserForAutoSuggestion($userId,$queryString){

        //get all users id from user_friends with like query on name
        $executor= self::getDbConnection()->prepare("SELECT users.id as id,users.first_name as name FROM users join user_friends on users.facebook_id=user_friends.friends_fb_id Where user_friends.user_fb_id=:user_fb_id AND users.first_name like :user_name", array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $executor->execute(array(":user_fb_id"=>$userId,":user_name"=>"%".$queryString."%"));
        $result= $executor->fetchAll(\PDO::FETCH_ASSOC);
        if(!empty($result))
            return $result;
        else
            return;
    }

 }