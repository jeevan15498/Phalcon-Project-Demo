<?php

class ErrorsController extends Phalcon\Mvc\Controller
{

    public function initialize() {
        
        $this->view->setTemplateAfter('guestLayout');
    }

    public function show404Action()
    {
    }

    public function show401Action()
    {
    }
}

