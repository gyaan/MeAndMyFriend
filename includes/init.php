<?php
/***include constant file ***/
require "config.php";

/**  include custom auto loader file */
require "myautoloader.php";


/*** include fb sdk***/
define('FACEBOOK_SDK_V4_SRC_DIR', __SITE_PATH .'/library/facebook-php-sdk/src/Facebook/');
require __SITE_PATH . '/library/facebook-php-sdk/autoload.php';



/*** a new registry object ***/
$registry = new \application\Registry();

/*** create the database registry object ** */
$registry->db = \model\db::getInstance();

//add important things to registry here

$registry->bootStrapPath= __SITE_PATH.'/library/bootstrap/';
?>
