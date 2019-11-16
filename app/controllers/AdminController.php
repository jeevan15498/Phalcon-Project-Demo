<?php

class AdminController extends \Phalcon\Mvc\Controller
{
    public function initialize() {
        
        $this->view->setTemplateAfter('adminLayout'); // Layout for Admin

        $this->tag->setTitle('Welcome to Admin Panel'); // By Default Page Title
    }

    public function indexAction()
    {

    }

}

