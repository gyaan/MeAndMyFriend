<?php
namespace controller;
use controller\rest\posts;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use application\baseController;
use model\userFriends;
use model\users;
use util\session;
use util\util;

class loginController extends baseController {

    function index()
    {

        //check if user is already logged in
        $sessionCheck=session::isUserLoggedIn();

        if($sessionCheck)
            util::redirect(baseUrl);

        FacebookSession::setDefaultApplication(FacebookAppId,FacebookAppSecret);
        $helper = new FacebookRedirectLoginHelper(baseUrl.'login/success/');

        /*** set a template variable ***/
        $this->registry->template->loginUrl = $helper->getLoginUrl(array('email','user_friends','public_profile'));
        /*** load the index template ***/
        $this->registry->template->show('login');

    }

    function success(){
        $sessionCheck = session::isUserLoggedIn();

        if($sessionCheck)
            util::redirect(baseUrl);

        FacebookSession::setDefaultApplication(FacebookAppId,FacebookAppSecret);
        $helper = new FacebookRedirectLoginHelper(baseUrl.'login/success/');
        try {
            $session = $helper->getSessionFromRedirect();
        } catch(FacebookRequestException $ex) {
            // When Facebook returns an error
            echo $ex->getMessage();
        } catch(\Exception $ex) {
            // When validation fails or other local issues
            echo $ex->getMessage();
        }
        if ($session) { //logged in successfully

            $access_token = $session->getToken();
            $appSecretProof = hash_hmac('sha256', $access_token,FacebookAppSecret);
            $request = new FacebookRequest($session, 'GET', '/me', array("appSecretProof" =>  $appSecretProof));
            $response = $request->execute();
            $graphObject = $response->getGraphObject();

            $userDetails = users::where(array('facebook_id'=>$graphObject->getProperty('id')));
            if(!empty($userDetails)){
                //found user now lets start sessions
                $sessionVariable['user_id']=$userDetails->id;
                $sessionVariable['email_id']=$userDetails->email_id;
                $sessionVariable['user_fb_id']=$userDetails->facebook_id;
                $sessionVariable['first_name']=$userDetails->first_name;
            }
            else{

                //add user details to db
                $lastInsertId=users::create(array('first_name'=>$graphObject->getProperty('first_name'),
                    'last_name'=>$graphObject->getProperty('last_name'),
                    'email_id'=>$graphObject->getProperty('email'),
                    'facebook_id'=>$graphObject->getProperty('id'),
                    'gender'=>$graphObject->getProperty('gender'),
                    'created_date'=> date("Y-m-d H:i:s")));

                //if there is no error in insertion
                $sessionVariable['email_id']=$graphObject->getProperty('email');
                $sessionVariable['user_id']=$lastInsertId;
                $sessionVariable['user_fb_id']=$graphObject->getProperty('id');
                $sessionVariable['first_name']=$graphObject->getProperty('first_name');

                //add user friends details
                try{
                    //lets get user friend list details
                    $request = new FacebookRequest(
                        $session,
                        'GET',
                        "/me/friends",
                        array("appsecret_proof" =>$appSecretProof)
                    );

                    $response = $request->execute();
                    $friendList = $response->getGraphObject()->asArray();

                    //if users friend used this app
                    if(!empty($friendList['data']))
                    {
                        foreach($friendList['data'] as $people){
                            userFriends::create(array('user_fb_id'=>$graphObject->getProperty('id'),
                                'friends_fb_id'=>$people->id,
                                'created_date'=>date("Y-m-d H:i:s")));
                        }
                    }

                    //add me default friend
                    userFriends::addAFriend($graphObject->getProperty('id'),'897931403583407');
                    \model\posts::welcomePosts($lastInsertId);
                }
                catch(\Facebook\FacebookRequestException $e){
                    print_r($e);
                }

            }

            if(!empty($sessionVariable)){
                session_start();
                $_SESSION['email_id']=$sessionVariable['email_id'];
                $_SESSION['user_id']=$sessionVariable['user_id'];
                $_SESSION['user_fb_id']=$sessionVariable['user_fb_id'];
                $_SESSION['first_name']=$sessionVariable['first_name'];
                //redirect to newsfeed page
                util::redirect(baseUrl);
            }
        }


    }


    function logout(){
        session_start();
        unset($_SESSION['email_id']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_fb_id']);
        unset($_SESSION['first_name']);
        session_destroy();
        //redirect to login page
        util::redirect(baseUrl);
    }
}