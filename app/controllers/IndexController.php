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

    /**
     * Create a login Page
     */
    public function loginAction() {

        if ($this->request->isPost()) {

            $dataSent = $this->request->getPost();
            $email = $dataSent["email"];
            $password = md5($dataSent["password"]);

            // $user = new Users();
            $user = Users::findFirst([
                'conditions' => 'email = ?1 and password = ?2',
                'bind' => [
                    1 => $email,
                    2 => $password,
                ]
            ]);

            if ($user) {

                // Check User Disable
                if ($user->active != 1) {
                    echo "User Disable";
                    $this->view->disable();
                } else {

                    # https://docs.phalconphp.com/en/3.3/session#start
        
                    // Set a session
                    $this->session->set('AUTH', [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'created' => $user->created,
                        'updated' => $user->updated,
                    ]);

                    if ($user->role === 1) {
                        // Redirect User Panel
                    } else if ($user->role === 2) {
                        // Redirect Admin Panel
                    } else {
                        // Exit;
                    }

                    return $this->response->redirect('index/login');
                }

            } else {

                echo "Invalid Email and Password.";
                $this->view->disable();
            }
        }
    }

    /**
     * Create a Sign up Page
     */
    public function signupAction() {

        // https://docs.phalcon.io/3.4/en/tutorial-base#designing-a-sign-up-form
        // https://docs.phalcon.io/3.4/en/db-odm#creating-updatingrecords

        if ($this->request->isPost()) {

            $user = new Users();
            
            // $dataSent = $this->request->getPost();
            // $user->name = $dataSent["name"];
            // $user->email = $dataSent["email"];
            // $user->password = $dataSent["password"];
            // $user->role = $dataSent["role"];
            // $user->created = time();
            // $user->updated = time();

            $user->setName($this->request->getPost('name'));
            $user->setEmail($this->request->getPost('email'));
            $user->setRole($this->request->getPost('role'));
            $user->setPassword(md5($this->request->getPost('password')));
            $user->setActive(1);
            $user->setCreated(time());
            $user->setUpdated(time());

            $success = $user->save();
    
            if ($success) {
                echo "Thanks for registering!";
                $this->view->disable();
            } else {
    
                $messages = $user->getMessages();
    
                foreach ($messages as $message) {
                    echo $message->getMessage(), "<br/>";
                }

                $this->view->disable();
            }
        }
    }

    public function logoutAction()
    {
        $this->session->destroy();
        return $this->response->redirect("");
    }
}

