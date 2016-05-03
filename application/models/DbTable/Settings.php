<?php

/**
 * @class       Application_Model_DbTable_User
 * @path        application/models/DbTable/User.php
 * @description This class contains functions to interact with the database table users.
 */
class Application_Model_DbTable_Settings extends Zend_Db_Table_Abstract
{

    // Specifies table name
    protected $_name = 'settings';

    public function addUserData($email)
    {
        try {
            $this->insert(array('email' => $email));
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function saveFacebookUrl($url)
    {
        try {
            if (isset($_SESSION['email'])) {
                $this->update(array('facebookFeedUrl' => $url), 'email="' . $_SESSION['email'] 
                    . '"');
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getFacebookUrl($email)
    {
        try {
            $row = $this->fetchRow("email = '" . $email . "'");
            if ($row) {
                return $row['facebookFeedUrl'];
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function saveTheme($theme)
    {
        try {
            if (isset($_SESSION['email'])) {
                $this->update(array('theme' => $theme), 'email="' . $_SESSION['email']
                    . '"');
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getTheme()
    {
        try {
            if (isset($_SESSION['email'])) {
                $row = $this->fetchRow("email = '" . $_SESSION['email'] . "'");
                if ($row) {
                    return $row['theme'];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

}
