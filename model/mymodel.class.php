<?php
namespace model;

use model\db;
abstract class myModel{

    protected static $tableName;
    private $attributes;

    function __construct(){}

    static function getDbConnection(){
        return db::getInstance();
    }

    function __get($name){
        if(isset($this->attributes[$name]))
            return $this->attributes[$name];
        else
            throw new \Exception("accessing unspecified value");
    }

    function __set($name,$value){
        $this->attributes[$name]=$value;
    }

    static function create(Array $arr){

        //define helper string
        $queryHelperStringFields='';
        $queryHelperStringValues='';
        $queryHelperArray=array();

        //create helper string
        foreach($arr as $key => $value){
            $queryHelperStringFields.=" ".$key.", ";
            $queryHelperStringValues.=" :".$key.", ";
            $queryHelperArray[":$key"]=$value;
        }
        //remove last ,
        $queryHelperStringFields=substr($queryHelperStringFields,0,strlen($queryHelperStringFields)-2);
        $queryHelperStringValues=substr($queryHelperStringValues,0,strlen($queryHelperStringValues)-2);

        try{
            $query = "INSERT INTO ".static::$tableName." (".$queryHelperStringFields.") VALUES (".$queryHelperStringValues.")";

            $executor = self::getDbConnection()->prepare($query);
            $executor->execute($queryHelperArray);

            //return last insert id
            return self::getDbConnection()->lastInsertId();
        }
        catch(\Exception $e){
            print_r($e);
        }
    }



    static function update($id,$values){

        $queryPrepareHelperString='';
        $queryPrepareHelperArray=array();
        foreach($values as $key=>$value){
            $queryPrepareHelperString.=$key." = ?, ";
            array_push($queryPrepareHelperArray,$value);
        }

        $queryPrepareHelperString=substr($queryPrepareHelperString,0,strlen($queryPrepareHelperString)-2);

        try{
            $query= "UPDATE ".static::$tableName." SET ".$queryPrepareHelperString." WHERE id = ".$id;
            $executor=self::getDbConnection()->prepare($query,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $executor->execute($queryPrepareHelperArray);
            return $id;
        }
        catch(\Exception $e){
            print_r($e);
        }
        return;
    }


    static function find($id){

        //assume each table have id field as  primary key
        $query = "SELECT * FROM ".static::$tableName."  WHERE id=:id";
        $executor = self::getDbConnection()->prepare($query,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $executor->execute(array(":id"=>$id));

        $object = $executor->fetchAll(\PDO::FETCH_ASSOC);

        //check if there is any object or not
        if(!empty($object) && count($object)>0)
        {
            list($object) = $object;

            //create instance of model
            $instance = new static();

            //assign values to model
            foreach($object as $key=>$value)
                $instance->$key=$value;

            ///yayyy we found the model lets return now
            return $instance;
        }

        //no object found
        return ;
    }
    //return all result
    static function all($offset=0,$limit=NULL){
        //assume each table have id field as  primary key
        $helperString=" ";

        if($limit!=NULL)
            $helperString=" LIMIT $offset, $limit";

        $query = "SELECT * FROM ".static::$tableName." ".$helperString;

        $executor = self::getDbConnection()->prepare($query,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $executor->execute();

        $objects = $executor->fetchAll(\PDO::FETCH_ASSOC);

        //check if there is any object or not
        if(!empty($objects) && count($objects)>0)
        {
            return $objects;
        }

        //no object found
        return ;
    }

    /**
     * @param array $arr
     * @return static
     */

    static function where(Array $arr){

        //variable to prepare the pdo queries
        $prepareStatement=' ';
        $whereArray=Array();

        //prepare where condition variable
        foreach($arr as $key=>$value){
            $prepareStatement.=$key." = :".$key." AND ";
            $whereArray[":$key"]=$value;
        }

        //remove last AND
        $actualPrepareStatement=substr($prepareStatement,0,strlen($prepareStatement)-4);

        //got the query
        $query = "SELECT * FROM ".static::$tableName."  WHERE ".$actualPrepareStatement;

        //let execute query
        $executor = self::getDbConnection()->prepare($query,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $executor->execute($whereArray);
        $object=$executor->fetchAll(\PDO::FETCH_ASSOC);

        if(!empty($object) && count($object)>0){
            list($object)=$object;
            $instance = new static();
            foreach($object as $key=>$value)
                $instance->$key=$value;
            return $instance;
        }

        //if we don't found any object
        return;
    }

    static function delete($id){

        try{
            $query = "DELETE  FROM ".static::$tableName." WHERE id = $id";
            $executor = self::getDbConnection()->prepare($query,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $executor->execute();
            return true;
        }
        catch(\Exception $e){
            print_r($e);
        }

    }


}