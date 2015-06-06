<?php
namespace controller;
use application\baseController;
Class error404Controller Extends baseController {

    public function index()
    {
        $this->registry->template->blog_heading = "Ahhhhhhhhhhh! this page doesn't exit";
        $this->registry->template->show("error404");
    }
}
?>
