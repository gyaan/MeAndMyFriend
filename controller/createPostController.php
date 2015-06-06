<?php
namespace controller;
use application\baseController;
use model\posts;
use model\taggedFriends;
use model\users;
use util\session;
use util\util;

class createPostController extends baseController {

    /**
     * @all controllers must contain an index method
     */
    function index()
    {
        $loginCheck=session::isUserLoggedIn();

        if(!$loginCheck)
            util::redirect('login');

        /*** load the index template ***/
        $this->registry->template->show('create_post');
    }

    function submit(){

        $loginCheck=session::isUserLoggedIn();

        if(!$loginCheck)
            util::redirect('login');
        //do something for message passing from one page to another

        //get the submitted values
        $content = empty($_POST['content'])?'blank content':$_POST['content'];

        $taggedUser=$_POST['tagFriends'];
        $taggedUserArray=explode(",",$taggedUser);
        $submittedUserId=$_SESSION['user_id'];

        //tag user itself to his post also
        array_push($taggedUserArray,$submittedUserId);

        //now lets store values in db
        $postId=posts::create(array('content'=>$content,'user_id'=>$submittedUserId,'created_date'=>date("Y-m-d H:i:s")));

        //tagging details
        foreach($taggedUserArray as $user){
            taggedFriends::create(array('post_id'=>$postId,'user_id'=>$user,'created_date'=>date("Y-m-d H:i:s")));
        }

        //redirect user to news feed page
        util::redirect(baseUrl);

    }

    //return auto suggestion for friends tagging

    function getAutoSuggestion(){

        $loginCheck=session::isUserLoggedIn();

        if(!$loginCheck)
            return json_encode(false);

        $return=array();
        $userId=$_SESSION['user_fb_id'];
        $queryString=$_GET['q'];

        if(empty($userId) || empty($queryString))
            return json_encode(false);

        $result = users::getFriendsListOfAUserForAutoSuggestion($userId,$queryString);
        if(!empty($result)){
            $return['friends']=$result;
            echo json_encode($return);
        }
        else
            return json_encode(false);
    }
}