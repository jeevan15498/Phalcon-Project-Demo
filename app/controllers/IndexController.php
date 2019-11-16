<?php

class IndexController extends ControllerBase
{

    public function initialize() {
        
        // https://docs.phalcon.io/3.4/en/views#using-templates
        $this->view->setTemplateAfter('guestLayout'); // Layout for Guest

        // https://docs.phalcon.io/3.4/en/tag#changing-dynamically-the-document-title
        $this->tag->setTitle('Guest User'); // By Default Page Title
    }

    public function indexAction()
    {
        $this->tag->prependTitle('Index -- '); // Current Page Title
    }
}

