<?php

class Universe_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
        new Universe_AclConfig();

        if($auth->hasIdentity()) {
            $acl = $_SESSION['acl'];
            $identity = $auth->getIdentity();
            try {
                $isAllowed = $acl->isAllowed(
                    $identity->role,
                    $request->getControllerName(),
                    $request->getActionName()
                );

                if (!$isAllowed) {
                    $this->endSessionAndExit();
                } else {
                    $_SESSION['userId'] = $identity->id;
                    $_SESSION['firstName'] = $identity->firstName;
                    $_SESSION['email'] = $identity->email;
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