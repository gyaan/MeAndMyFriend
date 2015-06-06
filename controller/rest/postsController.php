<?php
namespace controller\rest;
use application\restController;
use model\posts;
use model\users;

class postsController extends restController {

    /**
     * @all controllers must contain an index method
     */
    function fetchAll()
    {
        $data=array();
        $userId=$_GET['user_id'];

        if(empty($userId) || !isset($_GET['user_id']))
        $this->deliverResponse(200,'specify user id',$data);

        //check user id is valid id or not
        $user= users::find($userId);

        if(empty($user))
            $this->deliverResponse(200,'specified user id is not valid',$data);
        else{
            $data = posts::getPostOfUserId($userId);
            $this->deliverResponse(200,'success',$data);
        }

    }

    function fetch($objectId)
    {
        $object=posts::find($objectId);

        if(empty($object))
            $this->deliverResponse(200,'no posts found',array());
        else{
            $returnArray=array();
            $returnArray['id']=$object->id;
            $returnArray['content']=$object->content;
            $returnArray['created_date']=$object->created_date;
            $returnArray['user_id']=$object->user_id;
            $this->deliverResponse(200,'success',$returnArray);
        }

    }

    function create()
    {
        $newObject=array();
        $newObject['content'] = $_POST['content'];
        $newObject['user_id'] = $_POST['user_id'];
        $newObject['created_date']=date("Y-m-d H:i:s");

        $newPostId=posts::create($newObject);

        if(empty($newPostId))
            $this->deliverResponse('201','some problem',json_encode (json_decode ("{}")));
        else{
            $data=array();
            $data['new_user_id']=$newPostId;
            $this->deliverResponse('201','created',$data);
        }
    }

    function update($objectId)
    {
        $this->deliverResponse('200',"method doesn't support",json_encode (json_decode ("{}")));
    }

    function delete($objectId)
    {
        $check=posts::delete($objectId);
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