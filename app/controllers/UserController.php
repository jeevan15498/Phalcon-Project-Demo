<?php

class UserController extends \Phalcon\Mvc\Controller
{
    public function initialize() {
        
        $this->view->setTemplateAfter('userLayout'); // Layout for User
        
        $this->tag->setTitle('Welcome to User Panel'); // By Default Page Title
    }

    public function indexAction()
    {

    }

    public function getAction()
    {

    }
}

