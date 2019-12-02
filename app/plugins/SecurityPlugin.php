<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;
use security\Dbaccesscontrollist;
use security\Dbresource;
use security\Dbaction;
use security\Dbrole;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        // Check User AUTH Session
        $auth = $this->session->get('AUTH');
        if (!$auth){
            $role = 'Guest'; // By Default
        } else {
            $role = $auth['role'];
        }

        $controller = strtolower($dispatcher->getControllerName()); // Controller Name
        $action = strtolower($dispatcher->getActionName()); // Controller Method Name

        $acl = $this->getAcl();

        if (!$acl->isResource($controller)) {
            if ($dispatcher->getControllerName() != 'errors') {
                $dispatcher->forward([
                    'controller' => 'errors',
                    'action'     => 'show404'
                ]);
                return false;
            }
            return;
        }
        
        $allowed = $acl->isAllowed($role, $controller, $dispatcher->getActionName());
        if (!$allowed) {
            if ($dispatcher->getControllerName() != 'errors') {
                $dispatcher->forward(array(
                    'controller' => 'errors',
                    'action'     => 'show401'
                ));
                return false;
            }
            return;
        }
    }

    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl()
    {
        if (!isset($this->persistent->acl))
        {
            $acl = new AclList();

            $acl->setDefaultAction(Acl::DENY);

            // Fetch Data in the Database
            $dbRoles = DBRole::find();
            $dbResources = DBResource::find();
            $dbACLItems = DBaccesscontrollist::find();
            
            // Fetch Register User Roles
            foreach ($dbRoles as $dbRole) {
                $acl->addRole($dbRole->getRole());
            }
            
            foreach ($dbResources as $dbResource) {
                $dbActions = DbAction::find();

                $actions[] = null;
                foreach ($dbActions as $dbAction) {
                    array_push($actions, $dbAction->getAction());
                }

                $acl->addResource(new Resource($dbResource->getResource()), $actions);
            }

            foreach ($dbACLItems as $ACLItem) {
                $acl->allow($ACLItem->getRole(), $ACLItem->getResource(), $ACLItem->getAction());
            }
            
            // The acl is stored in session, APC would be useful here too
            $this->persistent->acl = $acl;
        }

        return $this->persistent->acl;
    }
}