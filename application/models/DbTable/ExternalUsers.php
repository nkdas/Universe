<?php

/**
 * @class       Application_Model_DbTable_ExternalUsers
 * @path        application/models/DbTable/ExternalUsers.php
 * @description Model to save and fetch information of the users
 *              connected using Google and Facebook.
 */
class Application_Model_DbTable_ExternalUsers extends Zend_Db_Table_Abstract
{

    protected $_name = 'externalUsers';

    /**
     * @function    saveData()
     * @description This function is used to save details of the users connected using
     *              Google and Facebook to the database.
     * @param       array $data array of user details
     *
     * @return      boolean
     */
    public function saveData($data)
    {
        try {
            $data = array(
                'email' => $data['email'],
                'name' => $data['name'],
                'loggedInWith' => $data['sender']
            );
            $this->insert($data);
            return true;
        } catch (Exception $exception) {
            $row = $this->fetchRow("email= '" . $data['email'] . "'");
            return $exception->getMessage() . ' ' . $row['loggedInWith'];
        }
    }

    /**
     * @function    checkUserExists()
     * @description This function is used to check if an email already exists
     *              in the database.
     * @param       string $email email id
     *
     * @return      boolean | string
     */
    public function checkUserExists($email)
    {
        $row = $this->fetchRow("email = '" . $email . "'");
        if (!$row) {
            return false;
        } else {
            return $row['loggedInWith'];
        }
    }

}

