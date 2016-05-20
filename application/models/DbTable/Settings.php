<?php

/**
 * @class       Application_Model_DbTable_Settings
 * @path        application/models/DbTable/Settings.php
 * @description This class contains functions to interact with the database table settings.
 */
class Application_Model_DbTable_Settings extends Zend_Db_Table_Abstract
{

    // Specifies table name
    protected $_name = 'settings';

    /**
     * @function    addUserData()
     * @description This function is used to save email of the newly registered user.
     * @param       string $email email id
     *
     * @return      boolean
     */
    public function addUserData($email)
    {
        try {
            $this->insert(array('email' => $email));
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @function    saveFacebookUrl()
     * @description This function is used to save facebook feed url to the database.
     * @param       string $url facebook feed url
     *
     * @return      boolean
     */
    public function saveFacebookUrl($url)
    {
        try {
            if (isset($_SESSION['email'])) {
                $this->update(
                    array('facebookFeedUrl' => $url), 
                    'email="' . $_SESSION['email'] . '"'
                );
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @function    getFacebookUrl()
     * @description This function is used to fetch facebook feed url from the database.
     * @param       string $email email id
     *
     * @return      boolean
     */
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

    /**
     * @function    saveTheme()
     * @description This function is used to save theme name and extension the database.
     * @param       string $theme theme name and action
     *
     * @return      boolean
     */
    public function saveTheme($theme)
    {
        try {
            if (isset($_SESSION['email'])) {
                $this->update(
                    array('theme' => $theme), 
                    'email="' . $_SESSION['email'] . '"'
                );
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @function    getTheme()
     * @description This function is used to fetch theme name and extension form the database.
     *
     * @return      boolean
     */
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
