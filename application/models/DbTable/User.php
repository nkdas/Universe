<?php

/**
 * @class       Application_Model_DbTable_User
 * @path        application/models/DbTable/User.php
 * @description This class contains functions to interact with the database table users.
 */
class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    // Specifies table name
    protected $_name = 'users';

    /**
     * @function    saveData()
     * @description This function gets an array of form data and saves them to the database.
     * @param       array $formData array of form data
     *
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
            );
            $this->insert($data);
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function checkUserExists($email)
    {
        $row = $this->fetchRow("email = '" . $email . "'");
        if (!$row) {
            return false;
        } else {
            return true;
        }
    }
}
