<?php

/**
 * @class       Application_Model_DbTable_User
 * @path        application/models/DbTable/User.php
 * @description This class contains functions to interact with the database table users.
 */
class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

    /**
     * @function    saveData()
     * @description This function gets an array of form data and saves them to the database.
     * @params      array $formData array of form data
     * @return      boolean returns true when success and false when failure.
     */
    public function saveData($formData)
    {
        try {
            $data = array(
                'email' => $formData['email'],
                'password' => $formData['password'],
                'firstName' => $formData['firstName'],
                'lastName' => $formData['lastName'],
                'role' => $formData['role']
            );
            $this->insert($data);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
