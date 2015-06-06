<?php
namespace model;
use model\myModel;
class userFriends extends myModel {

    protected static  $tableName='user_friends';


    static function addAFriend($userFbId,$friendFbId){

        userFriends::create(array('user_fb_id'=>$userFbId,
            'friends_fb_id'=>$friendFbId,
            'created_date'=>date("Y-m-d H:i:s")));
    }

}
