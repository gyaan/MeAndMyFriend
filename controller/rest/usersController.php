<?php
namespace controller\rest;

use application\restController;
use model\users;

class usersController extends restController {

    /**
     * @all controllers must contain an index method
     */
    function fetchAll()
    {
        $data=users::all();
        $this->deliverResponse(200,'success',$data);
    }

    function fetch($objectId)
    {

        $user=users::find($objectId);
        $data=array();
        if(!empty($user)){
            $data['id']=$user->id;
            $data['first_name']=$user->first_name;
            $data['last_name']=$user->last_name;
            $data['email_id']=$user->email_id;
            $data['facebook_id']=$user->facebook_id;
            $data['gender']=$user->gender;
            $data['created_date']=$user->created_date;
            $this->deliverResponse(200,'success',$data);
        }
        else
            $this->deliverResponse(200,'no user found',json_encode (json_decode ("{}")));

    }

    function create()
    {
        $newUser=array();
        $newUser['first_name']=urldecode($_POST['first_name']);
        $newUser['last_name']=urldecode($_POST['last_name']);
        $newUser['facebook_id']=urldecode($_POST['facebook_id']);
        $newUser['email_id']=urldecode($_POST['email_id']);
        $newUser['gender']=urldecode($_POST['gender']);
        $newUser['created_date']=date("Y-m-d H:i:s");

        $userId=users::create($newUser);

        if(empty($userId))
            $this->deliverResponse('201','some problem',json_encode (json_decode ("{}")));
        else{
            $data=array();
            $data['new_user_id']=$userId;
            $this->deliverResponse('201','created',$data);
        }

    }

    function update($objectId)
    {
        $updatedUser=array();
        $updatedUser['first_name']=urldecode($_POST['first_name']);
        $updatedUser['last_name']=urldecode($_POST['last_name']);
        $updatedUser['facebook_id']=urldecode($_POST['facebook_id']);
        $updatedUser['email_id']=urldecode($_POST['email_id']);
        $updatedUser['gender']=urldecode($_POST['gender']);

        $check = users::update($objectId,$updatedUser);
        if(empty($check))
            $this->deliverResponse('200',"problem with update",json_encode (json_decode ("{}")));
        else
        {
            $data['updatedRecord']=$check;
            $this->deliverResponse('201',"updated",$data);
        }

    }

    function delete($objectId)
    {
        $check=users::delete($objectId);
        $data=array();
        if($check){
            $data['deleteRecord']=$objectId;
            $this->deliverResponse('201','Deleted',$data);
        }
        else {
            $this->deliverResponse('200','record not deleted',json_encode (json_decode ("{}")));
        }
    }
}