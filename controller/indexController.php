<?php
namespace controller;
//display user news feed and complete things related to newsfeed
use application\baseController;
use model\posts;
use util\session;
use util\util;

Class indexController Extends baseController {

    /**
     * @all controllers must contain an index method
     */
    function index()
    {
        $sessionCheck=session::isUserLoggedIn();

        if(!$sessionCheck)
            util::redirect('login');

        $userId = $_SESSION['user_id'];

        /*** set a template variable ***/
        $this->registry->template->posts=posts::getPostOfUserId($userId,0,perPagePosts);

        $loadNextPage=(perPagePosts<posts::getPostsCountOfUserId($userId))?true:false;

        $this->registry->template->loadNextPage=$loadNextPage;

        /*** load the index template ***/
        $this->registry->template->show('news_feed');
    }


    function getUserPosts(){

        //check user is logged in or not
        $sessionCheck=session::isUserLoggedIn();

        if(!$sessionCheck)
            return;

        //get the page details
        $pageNumber=$_REQUEST['page_number'];

        //set default page number it start with 0
        if(!isset($pageNumber) || empty($pageNumber))
            $pageNumber=0;


        //get user id d details
        $userId = $_SESSION['user_id'];

        //get the total no of posts for a user
        $totalNumberOfPosts=(int)posts::getPostsCountOfUserId($userId);

        //if there is no posts
        if($totalNumberOfPosts<=0){
            echo json_encode(array('posts'=>array(),'loadNextPage'=>0));
            exit;
        }

        //last page number
        $lastPage=ceil($totalNumberOfPosts/(int)perPagePosts)-1;

        //if page number is greater then last page
        if($pageNumber>$lastPage){
            echo json_encode(array('posts'=>array(),'loadNextPage'=>0));
            exit;
        }

        //create offset according to page number
        $offset=((int)$pageNumber)*(int)perPagePosts;
        $posts= posts::getPostOfUserId($userId,$offset,perPagePosts);

        //check to show next page
        $loadNextPage=(int)($pageNumber+1)<=$lastPage?1:0;

        $return= array('posts'=>$posts,'loadNextPage'=>$loadNextPage);
        echo json_encode($return);

    }

}

?>
