<?php
 /*** error reporting on ***/
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);


/*** define the site path ***/
 $site_path = realpath(dirname(__FILE__));
 define ('__SITE_PATH', $site_path);

 /*** include the init.php file ***/
 include 'includes/init.php';

 /*** load the router ***/
 $registry->router = new \application\router($registry);

 /*** set the controller path ***/
 $registry->router->setPath (__SITE_PATH . '/controller');

 /*** load up the template ***/
 $registry->template = new application\Template($registry);

 /*** load the controller ***/
 $registry->router->loader();

?>
