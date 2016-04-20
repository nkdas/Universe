<?php

class Application_Model_DbTable_ExternalUsers extends Zend_Db_Table_Abstract
{

    protected $_name = 'externalUsers';

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

