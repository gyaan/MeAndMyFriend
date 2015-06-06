<?php
namespace application;

abstract class restController {

 /*
 * @registry object
 */
    protected $registry;

    function __construct($registry) {
        $this->registry = $registry;
    }

    /**
     * @all controllers must contain an index method
     */
    abstract function fetchAll(); //  /objects with GET Methods
    abstract function fetch($objectId); //  /objects/object_id  with GET method
    abstract function create();  //    /objects with POST method
    abstract function update($objectId); // /object/object_id with post method
    abstract function delete($objectId);

    function deliverResponse($status, $status_message, $data)
    {
        header('Content-Type:application/json');
        header("HTTP/1.1 $status $status_message ");
        echo json_encode($data);
    }

    function unknownMethod(){
        $this->deliverResponse('400','unknown method',json_encode (json_decode ("{}")));
    }

}