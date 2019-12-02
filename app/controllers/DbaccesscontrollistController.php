<?php

use security\Dbresource;
use security\Dbaction;
use security\Dbrole;
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use security\Dbaccesscontrollist;

class DbaccesscontrollistController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for dbaccesscontrollist
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, '\security\Dbaccesscontrollist', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "role";

        $dbaccesscontrollist = Dbaccesscontrollist::find($parameters);
        if (count($dbaccesscontrollist) == 0) {
            $this->flash->notice("The search did not find any dbaccesscontrollist");

            $this->dispatcher->forward([
                "controller" => "dbaccesscontrollist",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $dbaccesscontrollist,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
    }

    /**
     * Edits a dbaccesscontrollist
     *
     * @param string $role
     */
    public function editAction($role)
    {
        if (!$this->request->isPost()) {

            $dbaccesscontrollist = Dbaccesscontrollist::findFirstByrole($role);
            if (!$dbaccesscontrollist) {
                $this->flash->error("dbaccesscontrollist was not found");

                $this->dispatcher->forward([
                    'controller' => "dbaccesscontrollist",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->role = $dbaccesscontrollist->role;

            $this->tag->setDefault("role", $dbaccesscontrollist->getRole());
            $this->tag->setDefault("action", $dbaccesscontrollist->getAction());
            $this->tag->setDefault("resource", $dbaccesscontrollist->getResource());
            
        }
    }

    /**
     * Creates a new dbaccesscontrollist
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "dbaccesscontrollist",
                'action' => 'index'
            ]);

            return;
        }

        $dbaccesscontrollist = new Dbaccesscontrollist();
        $dbaccesscontrollist->setRole($this->request->getPost("role"));
        $dbaccesscontrollist->setAction($this->request->getPost("action"));
        $dbaccesscontrollist->setResource($this->request->getPost("resource"));
        

        if (!$dbaccesscontrollist->save()) {
            foreach ($dbaccesscontrollist->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "dbaccesscontrollist",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("dbaccesscontrollist was created successfully");

        $this->dispatcher->forward([
            'controller' => "dbaccesscontrollist",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a dbaccesscontrollist edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "dbaccesscontrollist",
                'action' => 'index'
            ]);

            return;
        }

        $role = $this->request->getPost("role");
        $dbaccesscontrollist = Dbaccesscontrollist::findFirstByrole($role);

        if (!$dbaccesscontrollist) {
            $this->flash->error("dbaccesscontrollist does not exist " . $role);

            $this->dispatcher->forward([
                'controller' => "dbaccesscontrollist",
                'action' => 'index'
            ]);

            return;
        }

        $dbaccesscontrollist->setRole($this->request->getPost("role"));
        $dbaccesscontrollist->setAction($this->request->getPost("action"));
        $dbaccesscontrollist->setResource($this->request->getPost("resource"));
        

        if (!$dbaccesscontrollist->save()) {

            foreach ($dbaccesscontrollist->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "dbaccesscontrollist",
                'action' => 'edit',
                'params' => [$dbaccesscontrollist->role]
            ]);

            return;
        }

        $this->flash->success("dbaccesscontrollist was updated successfully");

        $this->dispatcher->forward([
            'controller' => "dbaccesscontrollist",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a dbaccesscontrollist
     *
     * @param string $role
     */
    public function deleteAction($role)
    {
        $dbaccesscontrollist = Dbaccesscontrollist::findFirstByrole($role);
        if (!$dbaccesscontrollist) {
            $this->flash->error("dbaccesscontrollist was not found");

            $this->dispatcher->forward([
                'controller' => "dbaccesscontrollist",
                'action' => 'index'
            ]);

            return;
        }

        if (!$dbaccesscontrollist->delete()) {

            foreach ($dbaccesscontrollist->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "dbaccesscontrollist",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("dbaccesscontrollist was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "dbaccesscontrollist",
            'action' => "index"
        ]);
    }

    /**
     * Set Access Control List in the Database
     * @param {string} $resource {Controller Name}
     */
    public function setAccessControlAction($resource)
    {
        /**
         * Insert Controller and Controller Functions in the Database Table {dbresource, dbaction}
         * @dbresource => Insert Controller Name
         * @dbaction => Insert Controller Functions
         */
        $output = $this->populateAclAction($resource);
        if (isset($output['status']) && $output['status'] === false) {
            echo $output['error'];

            // Disable View File Content
            $this->view->disable();
        }

        // Passing controller name to view file
        $this->view->resource = $resource;
        // Fetch All User Roles in the Database Table {dbrole}
        $this->view->roles = Dbrole::find();
        // Fetch {$resource} Controller Function Names in the Database Table {dbaction}
        $this->view->actions = Dbaction::findByResource($resource);
        // Fetch {$resource} Controller Access List in the Database Table {dbaccesscontrollist}
        $this->view->aclItems = Dbaccesscontrollist::findByResource($resource);
    }

    /**
     * [Private]: Don't Open in Browser URL
     */
    protected function populateAclAction($resource)
    {
        $dir = "../app/controllers/"; # OUTPUT: ../app/controllers/
        $className = (ucfirst($resource . "Controller")); # OUTPUT: {$resource}Controller, ex- UserController
        $controllerFile = $dir . $className . ".php"; # OUTPUT: ../app/controllers/UserController.php

        // trying to include a file with the same name as the current script causes a conflict
        if (strcmp($resource,"dbaccesscontrollist") != 0) {
            if ((@include $controllerFile) === false) {
                // $this->flash->error("No such Resource/Controller File");
                // return;
                return ['status' => false, 'error' => "No such Resource/Controller File"];
            }
        }

        $thisClass = new $className(); // Create a {$resource} Controller Object
        $funcs = get_class_methods($thisClass); // Get {$resource} Class Methods
        unset($thisClass); // Remove Variable

        $resourceModel = new Dbresource(); // Create a {Dbresource} Model Object
        $resourceModel->setResource($resource); // Set {$resource} Controller Name in the Resource Database table field Name

        // Insert {$resource} Controller Name in the Database
        if (!$resourceModel->save()) {
            // Validation OR Database Errors
            foreach ($resourceModel->getMessages() as $message) {
                $this->flash->error($message);
            }
            return;
        }

        // Create an action in the database for each of the functions of the controller                    
        foreach ($funcs as $func) {
            if (strpos($func, "Action")) {
                // Create a {Dbaction} Model Object
                $action = new Dbaction();
                $action->setResource($resource);
                $action->setAction(substr($func, 0, -6));
                // Insert Data in Database 
                if (!$action->save()) {
                    // Validation OR Database Errors
                    foreach ($action->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    return;
                }
            }
        }
        
    }

    /**
     * Save Access Control List in the Database
     */
    public function saveAccessControlAction()
    {
        if ($this->request->isPost()) {

            $resource = $this->request->getPost('resource');
            
            // Delete all pre-existing access control settings for this resource
            $dbACLCurrentItems = Dbaccesscontrollist::findByResource($resource);
            foreach ($dbACLCurrentItems as $dbACLCurrentItem) {
                $dbACLCurrentItem->delete();
            }

            $aclItemsArray = $this->request->getPost('aclItem');
            $msg = "No updates to the Acl";
            if (!empty($aclItemsArray)) {
                foreach ($aclItemsArray as $role => $actionsArray) {
                    foreach ($actionsArray as $action => $y) {
                        $aclItem=new Dbaccesscontrollist();
                        $aclItem->setRole($role);
                        $aclItem->setResource($resource);
                        $aclItem->setAction($action);
                        $aclItem->save();
                        $msg = "The Acl has been updated";
                    }
                }
            }

            echo $msg;

            // Disable View File Content
            $this->view->disable();

        } else {
            return $this->response->redirect();
        }
    }
}
