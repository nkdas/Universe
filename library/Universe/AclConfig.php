<?php

class Universe_AclConfig extends Zend_Acl
{
    public function __construct()
    {

        // Add a new role called "guest"
        $this->addRole(new Zend_Acl_Role('guest'));
        // Add a role called user, which inherits from guest
        $this->addRole(new Zend_Acl_Role('user'), 'guest');
        // Add a role called admin, which inherits from user
        $this->addRole(new Zend_Acl_Role('admin'), 'user');

        // Add some resources in the form controller::action
        $this->add(new Zend_Acl_Resource('index', 'index'));

        // Allow guests to see the index page
        $this->allow('guest', 'index', 'index');
        // Allow users to access logout and the index action from the user controller
        $this->allow('user', 'index','signOut');
        //$this->allow('user', 'index::settings');
        // Allow admin to access admin controller, index action
        //$this->allow('admin', 'admin::index');

        $_SESSION['acl'] = $this;

    }
}
