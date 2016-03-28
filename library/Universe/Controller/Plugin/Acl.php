<?php

class Universe_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
        $acl = new Universe_AclConfig();
        if($auth->hasIdentity()) {
            $registry = Zend_Registry::getInstance();
            $acl = $registry->get('acl');
            $identity = $auth->getIdentity();
            $registry->set('userName', $identity->firstname);
            $registry->set('isSignedIn', true);
            try {
                $isAllowed = $acl->isAllowed(
                    $identity->role,
                    $request->getControllerName(),
                    $request->getActionName()
                );

                if (!$isAllowed) {
                    $this->endSessionAndExit();
                }
            } catch (Exception $ex) {
                $this->endSessionAndExit();
            }
        } else {

            // If user is not logged in and is not requesting index page
            // then redirect to index page.
            if (!$auth->hasIdentity()
                && $request->getControllerName() != 'index'
                && $request->getActionName()     != 'index') {
                $this->endSessionAndExit();
            }
        }
    }

    public function endSessionAndExit()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
        $redirector->gotoUrlAndExit('index/index');
    }

}