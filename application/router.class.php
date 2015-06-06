<?php
namespace application;
class router {
    /*
    * @the registry
    */
    private $registry;

    /*
    * @the controller path
    */
    private $path;

    private $args = array();

    private $isRestController=false;

    private $objectId;

    public $file;

    public $controller;

    public $action;

    function __construct($registry) {
        $this->registry = $registry;
    }

    /**
     *
     * @set controller directory path
     *
     * @param string $path
     *
     * @return void
     *
     */
    function setPath($path) {

        /*** check if path i sa directory ***/
        if (is_dir($path) == false)
        {
            throw new Exception ('Invalid controller path: `' . $path . '`');
        }
        /*** set the path ***/
        $this->path = $path;
    }


    /**
     *
     * @load the controller
     *
     * @access public
     *
     * @return void
     *
     */
    public function loader()
    {
        /*** check the route ***/
        if(isset($_GET['rc']))
            $this->getRestController();
        else
            $this->getController();

        /*** if the file is not there diaf ***/
        if (is_readable($this->file) == false)
        {
            $this->file = $this->isRestController===false? $this->path.'/error404.php':$this->path.'/rest/error404.php';
            $this->controller = 'error404';
        }

        /*** include the controller ***/
        include $this->file;

        /*** a new controller class instance ***/
        if($this->isRestController)
            $class = "\controller\\rest\\".$this->controller . 'Controller';
        else
            $class = "\controller\\".$this->controller . 'Controller';

        $controller = new $class($this->registry);

        /*** check if the action is callable ***/
        if (is_callable(array($controller, $this->action)) == false)
        {
            $action =$this->isRestController==true?'fetchAll':'index';
        }
        else
        {
            $action = $this->action;
        }
        /*** run the action ***/
        if(!empty($this->objectId))
            $controller->$action($this->objectId);
        else
            $controller->$action();
    }


    /**
     *
     * @get the controller
     *
     * @access private
     *
     * @return void
     *
     */
    private function getController() {

        /*** get the route from the url ***/
        $route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

        if (empty($route))
        {
            $route = 'index';
        }
        else
        {
            /*** get the parts of the route ***/
            $parts = explode('/', $route);
            $this->controller = $parts[0];
            if(isset( $parts[1]))
            {
                $this->action = $parts[1];
            }
        }

        if (empty($this->controller))
        {
            $this->controller = 'index';
        }

        /*** Get action ***/
        if (empty($this->action))
        {
            $this->action = 'index';
        }

        /*** set the file path ***/
        $this->file = $this->path .'/'. $this->controller . 'Controller.php';
    }



    private function getRestController() {

        $this->isRestController=true;
        $withObjectId=false;
        /*** get the route from the url ***/
        $route = (empty($_GET['rc'])) ? '' : $_GET['rc'];

        if (empty($route))
        {
            $route = 'posts';
        }
        else
        {
            /*** get the parts of the route ***/
            $parts = explode('/', $route);
            $this->controller = $parts[0];
            if(isset( $parts[1]))
            {
                $this->objectId= $parts[1];
                $withObjectId=true;
            }

        }

        if (empty($this->controller))
        {
            $this->controller = 'posts';
        }

        $this->action= $this->getActionForRestController($_SERVER['REQUEST_METHOD'],$withObjectId);

        /*** set the file path ***/
        $this->file = $this->path .'/rest/'. $this->controller . 'Controller.php';
    }

    private function getActionForRestController($httpMethod,$withObjectId=false){

        if($httpMethod=='GET'&&$withObjectId==true)
            return 'fetch';
        else if($httpMethod=='POST'&&$withObjectId==false)
            return 'create';
        else if($httpMethod=='GET'&&$withObjectId==false)
            return 'fetchAll';
        else if($httpMethod=='POST'&&$withObjectId==true)
            return 'update';
        else if($httpMethod=='DELETE' && $withObjectId==true)
            return 'delete';
        else
            return 'unknownMethod';

    }

}

?>
