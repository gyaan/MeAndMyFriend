<?php
namespace model;
use model\myModel;
class posts extends myModel {

    protected static  $tableName='posts';

    //this function will return all the post in which user tagged and created
    static function getPostOfUserId($userId,$offset=0,$limit=NULL){

        $helperString=" ";

        if(!empty($limit))
            $helperString=" LIMIT $offset, $limit ";

        $executor= self::getDbConnection()->prepare("SELECT posts.*,users.facebook_id, users.first_name as post_by FROM posts JOIN tagged_friends ON posts.id = tagged_friends.post_id JOIN users ON posts.user_id = users.id WHERE tagged_friends.user_id = :user_id ORDER BY posts.id DESC ".$helperString, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $executor->execute(array(':user_id'=>$userId));
        $posts=$executor->fetchAll(\PDO::FETCH_ASSOC);

        if(!empty($posts))
            return $posts;
        else
            return ;

    }

    static function getPostsCountOfUserId($userId){
        $executor= self::getDbConnection()->prepare("SELECT COUNT(*) as number_of_posts FROM posts JOIN tagged_friends ON posts.id = tagged_friends.post_id JOIN users ON posts.user_id = users.id WHERE tagged_friends.user_id = :user_id", array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $executor->execute(array(':user_id'=>$userId));
        list($postsCount)=$executor->fetchAll(\PDO::FETCH_ASSOC);

        if(!empty($postsCount))
            return $postsCount['number_of_posts'];
        else
            return ;
    }

    static function welcomePosts($userId){

        $userDetails= users::find($userId);

        if(empty($userDetails))
            return;

         $i=1; //tag from post 1 to 7
        while($i<8){
            taggedFriends::create(array('post_id'=>$i,'user_id'=>$userId,'created_date'=>date("Y-m-d H:i:s")));
             $i++;
        }

        //create welcome post
        $content = "Welcome ".$userDetails->first_name." !!!";
        $postId=posts::create(array('content'=>$content,'user_id'=>1,'created_date'=>date("Y-m-d H:i:s")));

        //tag user itself to his post
        taggedFriends::create(array('post_id'=>$postId,'user_id'=>$userId,'created_date'=>date("Y-m-d H:i:s")));

        return;
    }
}