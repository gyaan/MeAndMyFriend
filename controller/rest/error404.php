<?php
namespace controller\rest;

class error404Controller extends \application\restController{


    function fetchAll()
    {
        $this->deliverResponse('200',"its 404 dude !!!",json_encode (json_decode ("{}")));

    }

    function fetch($objectId)
    {
        $this->deliverResponse('200',"its 404 dude !!!",json_encode (json_decode ("{}")));
    }

    function create()
    {
        $this->deliverResponse('200',"its 404 dude !!!",json_encode (json_decode ("{}")));
    }

    function update($objectId)
    {
        $this->deliverResponse('200',"its 404 dude !!!",json_encode (json_decode ("{}")));
    }

    function delete($objectId)
    {
        $this->deliverResponse('200',"its 404 dude !!!",json_encode (json_decode ("{}")));
    }

    function unknownMethod(){
        $this->deliverResponse('200',"its 404 dude !!!",json_encode (json_decode ("{}")));
    }
}