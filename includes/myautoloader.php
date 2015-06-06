<?php
spl_autoload_register(function($className) {

//    print_r($className);
    //create path for classes
    $pathAttributes = explode("\\",$className);

    $originalPath = '';

    //create complete path string
    foreach($pathAttributes as $path)
        $originalPath = $originalPath."/".$path;

    //remove first /
    $originalPath= substr($originalPath,1,strlen($originalPath));

    //final class path
    $className = strtolower($originalPath);
    $filePath = __SITE_PATH."/" .$className . '.class.php';

    //include the class
    if (file_exists($filePath)) require_once $filePath;

});
