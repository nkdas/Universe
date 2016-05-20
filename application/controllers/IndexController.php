<?php

/**
 * @class       IndexController
 * @path        application/controllers/IndexController.php
 * @description This class acts as the IndexController.
 */
class IndexController extends Zend_Controller_Action
{
    private $_users = null;
    private $_externalUsers = null;
    private $_twitter = null;
    private $_facebook = null;
    private $_settings = null;

    public function init()
    {
        $this->_users = new Application_Model_DbTable_User();
        $this->_externalUsers = new Application_Model_DbTable_ExternalUsers();
        $this->_twitter = new Application_Model_Twitter_Twitter();
        $this->_facebook = new Application_Model_Facebook_Facebook();
        $this->_settings = new Application_Model_DbTable_Settings();
    }

    /**
     * @function    indexAction()
     * @description This function performs the actions to be taken when
     *              index page is loaded.
     *
     * @return      void
     */
    public function indexAction()
    {
        // Check if a user is already logged in and change
        // the sign in icon to sign out
        try {
            $this->view->isSignedIn = false;
            if (isset($_SESSION['firstName'])) {
                $this->view->isSignedIn = true;
                $this->view->firstName = $_SESSION['firstName'];
                if (isset($_SESSION['loggedInWith'])) {
                    if ('google' == $_SESSION['loggedInWith']) {
                        $this->view->authLink 
                        = "<a href='http://universe.com/index/sign-out' " 
                        . "onclick='signOut();' title='Sign Out'>"
                        . "<span class='fa fa-sign-out'></span></a>";
                    } else {
                        $this->view->authLink 
                        = "<a href='http://universe.com/index/sign-out' " 
                        . "onclick='fb_Logout();' title='Sign Out'>" 
                        . "<span class='fa fa-sign-out'></span></a>";
                    }
                } else {
                    $this->view->authLink = "<a href='http://universe.com/index/sign-out' title='Sign Out'>
                            <span class='fa fa-sign-out'></span></a>";
                }
            } else {
                $this->view->authLink = "<a href='#signIn' data-toggle='tab' title='Sign In'>
                            <span class='menu-open fa fa-sign-in'></span></a>";
            }
        }catch(Exception $exception){
            error_log($exception->getMessage());
        }

        // Display the WebSearch form
        $webSearchForm = new Application_Form_WebSearch();
        $this->view->webSearchForm = $webSearchForm;

        // Displays the SignIn form in index view
        $signInForm = new Application_Form_SignIn();
        $this->view->signInForm = $signInForm;

        // Displays the SignUp form in index view
        $signUpForm = new Application_Form_SignUp();
        $this->view->signUpForm = $signUpForm;
        // Displays the news feeds
        $newsForm = new Application_Form_News();
        $this->view->newsForm = $newsForm;


        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();

            // If user is trying to sign in
            if ('Sign in' == ($formData['submit'])) {
                if ($signInForm->isValid($formData)) {
                    $this->signIn($formData);
                } else {
                    $messageArrays = $signInForm->getMessages();
                    $message = $this->prepareMessages($messageArrays);
                    $this->view->message = $message;
                }
            }

            // If user is trying to sign up
            if ('Sign up' == ($formData['submit'])) {
                if ($signUpForm->isValid($formData)) {
                    $this->signUp($formData);
                } else {
                    $messageArrays = $signUpForm->getMessages();
                    $message = $this->prepareMessages($messageArrays);
                    $this->view->message = $message;
                }
            }

            // If user is performing web search with bing
            if (array_key_exists('bingButton', $formData)) {
                $this->redirect('http://bing.com/search?q=' . urlencode($formData['searchBox']));
            }
            // If user is performing web search with google
            if (array_key_exists('googleButton', $formData)) {
                $this->redirect('http://google.com/search?q=' . urlencode($formData['searchBox']));
            }
            // If user is performing web search with yahoo
            if (array_key_exists('yahooButton', $formData)) {
                $this->redirect('http://search.yahoo.com/search?p=' . urlencode($formData['searchBox']));
            }
            // If user is performing web search with wikipedia
            if (array_key_exists('wikipediaButton', $formData)) {
                $this->redirect('http://wikipedia.org/wiki/' . str_replace(' ', '_', $formData['searchBox']));
            }
        }

        // Check if the app has the OAuthToken for Twitter
        // If not, call the function getOAuthToken()
        // Else call the function twitterAction() to get user-timeline

        if (isset($_SESSION['isOAuthTokenPresent'])
            && ('1' == $_SESSION['isOAuthTokenPresent'])) {
            try {
                $this->twitterAction();
            } catch (Exception $exc) {
                $_SESSION['isOAuthTokenPresent'] = '0';
                error_log($exc->getMessage());
                $this->view->twitterData 
                = "<p>Sorry! Twitter API may be down for maintenance.</p>";
            }
        } else {
            $this->view->twitterData = "<p>Please refresh the widget by clicking on  
            <span class='fa fa-refresh'></span>  to get your feeds</p>";
        }

    }

    /**
     * @function    signIn()
     * @description This function is used to authenticate the user.
     * @param       array $formData array of form data
     *
     * @return      void
     */
    public function signIn($formData)
    {
        try {
            $authAdapter = new Zend_Auth_Adapter_DbTable(
                $this->_users->getAdapter(),
                'users'
            );

            $authAdapter->setIdentityColumn('email')
                ->setCredentialColumn('password');
            $authAdapter->setIdentity($formData['email'])
                ->setCredential($formData['password']);

            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($authAdapter);

            if ($result->isValid()) {
                $auth->getStorage()->write($authAdapter->getResultRowObject(null, 'password'));
                $this->_redirect('index');
            } else {
                $this->view->message = "Invalid email or password. Please try again.";
            }

        } catch (Exception $exception) {
            error_log($exception->getMessage());
        }
    }

    /**
     * @function    signUp()
     * @description This function is used to register the user.
     * @param       array $formData array of form data
     *
     * @return      void
     */
    public function signUp($formData)
    {
        $status = $this->_externalUsers->checkUserExists($_POST['email']);
        if (!($status)) {
            $status = $this->_users->saveData($formData);
            if ($status) {
                $this->_settings->addUserData($formData['email']);
                $this->signIn($formData);
            } else {
                if (false !== strpos($status, 'Duplicate entry')) {
                    $this->view->message = 'oops! This Email Id is already with us.<br>'
                    . 'It seems You have already registered.<br><br>If so, Please Sign in<br><br>'
                    . 'If not, make sure you are using the correct Email Id';
                } else {
                    $this->view->message = 'Sorry! Something is wrong, we are unable'
                    . ' to process your request';
                }
            }
        } else {
            $this->view->message = 'It seems like you have already registered with ' . $status;
        }
    }

    /**
     * @function    signOutAction()
     * @description This function is used to sign out a user from the app.
     *
     * @return      void
     */
    public function signOutAction()
    {
        session_destroy();
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
        $redirector->gotoUrlAndExit('index');
    }

    /**
     * @function    twitterAction()
     * @description This function gets the users home-timeline
     *              and displays it in the widget.
     *
     * @return      void
     */
    public function twitterAction()
    {
        $this->view->twitterData = $this->_twitter->getAccessToken();
    }

    /**
    * @function     twitterRefreshAction()
    * @description  This function is used to get the oAuthToken
    *               from Twitter to be exchanged to get the AccessToken
    *
    * @return       void
    */
    public function twitterRefreshAction()
    {
        try {
            $this->_twitter->getOAuthToken();
        } catch (Exception $exc) {
            $this->view->twitterData 
                = "<p>Sorry! Twitter API may be down for maintenance.</p>";
        }
    }

    /**
    * @function    prepareMessages()
    * @description This function is used to prepare messages to be shown to 
    *              the users
    * @param       array $messageArrays array of messgaes
    *
    * @return      void
    */
    public function prepareMessages($messageArrays)
    {
        $messageString='';
        foreach ($messageArrays as $messageArray) {
            foreach ($messageArray as $message) {
                if ($message != '') {
                    $messageString .= $message . '<br>';
                }
            }
        }
        return $messageString;
    }

    /**
    * @function     setGoogleSessionAction()
    * @description  This function is used to set session when
    *               a user signs in with Google or Facebook
    *
    * @return       void
    */
    public function setGoogleSessionAction()
    {
        if (isset($_SESSION['firstName'])) {
            $state = array('status' => 'sessionAlreadySet');
            echo json_encode($state);
            exit;
        } else {
            try{
                if (isset($_POST['name']) && isset($_POST['sender'])) {
                    if (!($this->_users->checkUserExists($_POST['email']))) {
                        $status = $this->_externalUsers->saveData($_POST);
                        if (true === $status) {
                            $this->_settings->addUserData($_POST['email']);
                            $_SESSION['firstName'] = $_POST['name'];
                            $_SESSION['loggedInWith'] = $_POST['sender'];
                            $_SESSION['email'] = $_POST['email'];
                            $state = array('status' => 'ok');
                            echo json_encode($state);
                            exit;
                        } else if (false !== strpos($status, 'Duplicate entry')) {
                            if (false === strpos($status, $_POST['sender'])) {
                                $state = array('status' => 'duplicate');
                                echo json_encode($state);
                                exit;
                            } else {
                                $this->_settings->addUserData($_POST['email']);
                                $_SESSION['firstName'] = $_POST['name'];
                                $_SESSION['loggedInWith'] = $_POST['sender'];
                                $_SESSION['email'] = $_POST['email'];
                                $state = array('status' => 'ok');
                                echo json_encode($state);
                                exit;
                            }
                        } else {
                            $state = array('status' => 'fail');
                            echo json_encode($state);
                            exit;
                        }
                    }
                }
            } catch (Exception $e) {
                $state = array('status' => 'fail');
                echo json_encode($state);
            }
        }
        exit;
    }

    /**
    * @function     fecebookRefreshAction()
    * @description  This function is used to get facebook feeds from
    *               the model Facebook
    *
    * @return       void
    */
    public function facebookRefreshAction()
    {
        $url = $this->_settings->getFacebookUrl($_SESSION['email']);
        $feed = $this->_facebook->getFacebookFeed($url);
        $feed = trim($feed);
        if ('<h3></h3>' == $feed) {
            $feed = "<h3>How to use this widget?</h3>"
                . "<p>Sign In to Facebook and click on notifications</p>"
                . "<img src='images/facebook/facebook2.png'><br><br>"
                . "<p>From the notifications dropdown, Select 'See All'</p>"
                . "<img src='images/facebook/facebook3.png'><br><br>"
                . "<p>You will be redirected to your notifications page</p>"
                . "<p>In the notifications page, click on RSS</p>"
                . "<img src='images/facebook/facebook.png'><br><br>"
                . "<p>You will be redirected to RSS page</p>"
                . "<img src='images/facebook/facebook5.png'><br><br>"
                . "<p>Copy the url from the address bar</p>"
                . "<img src='images/facebook/facebook4.png'><br><br>"
                . "<p>From the sidebar open Settings by clicking "
                . "<span class='menu-open fa fa-cogs'></span><br>"
                . " enter the copied url and save.<br><br>Now refresh your widget</p>";

        }

        echo $feed;
        exit;
    }

    /**
    * @function     saveFacebookUrlAction()
    * @description  This function is used to save facebook feed url to the database
    *
    * @return       void
    */
    public function saveFacebookUrlAction()
    {
        $url = $_POST['facebookUrl'];
        if ($this->_settings->saveFacebookUrl($url)) {
            $state = array('status' => 'success');
        } else {
            $state = array('status' => 'fail');
        }
        echo json_encode($state);
        exit;
    }

    /**
    * @function     saveThemeAction()
    * @description  This function is used to save the theme name and extension
    *               to the database
    *
    * @return       void
    */
    public function saveThemeAction()
    {
        $theme = $_POST['theme'];
        if ($this->_settings->saveTheme($theme)) {
            $state = array('status' => 'success');
        } else {
            $state = array('status' => 'fail');
        }
        echo json_encode($state);
        exit;
    }

    /**
    * @function     getThemeAction()
    * @description  This function is used to get theme name and extension to the database
    *
    * @return       void
    */
    public function getThemeAction()
    {
        $theme = $this->_settings->getTheme();

        if ($theme) {
            $state = array('status' => $theme);
        } else {
            $state = array('status' => 'fail');
        }
        echo json_encode($state);
        exit;
    }
}
