<?php

/**
 * @class       IndexController
 * @path        application/controllers/IndexController.php
 * @description This class acts as the IndexController.
 *
 */

class IndexController extends Zend_Controller_Action
{

    private $_users = null;

    private $_twitter = null;

    public function init()
    {
        $this->_users = new Application_Model_DbTable_User();
        $this->_twitter = new Application_Model_Twitter_Twitter();
    }

    /**
     * @function    indexAction()
     * @description This function performs the actions to be taken when index page is
     * loaded.
     * @params      none
     * @return      none
     *
     */
    public function indexAction()
    {

        // Check if a user is already logged in and change
        // the sign in icon to sign out
        try {
            $registry = Zend_Registry::getInstance();
            if (isset($registry)){
                $this->view->userName = $registry->get('userName');
                $this->view->isSignedIn = $registry->get('isSignedIn');
            }
        }catch(Exception $exception){
            error_log($exception->getMessage());
        }

        // Display the WebSearch form
        $webSearchForm = new Application_Form_WebSearch();
        $this->view->webSearchForm = $webSearchForm;

        // Displays the SignIn form in index view
        $signInForm = new Application_Form_SignIn();
        $signInForm->submit->setLabel('Signin');
        $this->view->signInForm = $signInForm;

        // Displays the SignUp form in index view
        $signUpForm = new Application_Form_SignUp();
        $signUpForm->submit->setLabel('Signup');
        $this->view->signUpForm = $signUpForm;

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();

            // If user is trying to sign in
            if ('Sign in' == ($formData['submit']) && $signInForm->isValid($formData)) {
                $this->signIn($formData);
            }
            // If user is trying to sign up
            if ('Sign up' == ($formData['submit']) && $signUpForm->isValid($formData)) {
                $this->signUp($formData);
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
            && ('1' == $_SESSION['isOAuthTokenPresent']))
        {
            try {
                $this->twitterAction();
            } catch (Exception $exc) {
                $_SESSION['isOAuthTokenPresent'] = '0';
                error_log($exc->getMessage());
            }
        }
    }

    /**
     * @function    signIn()
     * @description This function is used to authenticate the user.
     * @params      array $formData array of form data
     * @return      none
     *
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
        } catch (Exception $exception) {
            error_log($exception->getMessage());
        }

        if($result->isValid()) {
            $auth->getStorage()->write($authAdapter->getResultRowObject(null, 'password'));
            $this->_redirect('index/index');
        } else {
            $this->view->message = "Invalid email or password. Please try again.";
        }
    }

    /**
     * @function    signUp()
     * @description This function is used to register the user.
     * @params      array $formData array of form data
     * @return      none
     *
     */
    public function signUp($formData)
    {
        $users = new Application_Model_DbTable_User();
        $status = $users->saveData($formData);
        if ($status) {
            $this->_redirect('index/index');
        }
    }

    /**
     * @function    signOutAction()
     * @description This function is used to sign out a user from the app.
     * @params      none
     * @return      none
     *
     */
    public function signOutAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
        $redirector->gotoUrlAndExit('index/index');
    }

    /**
     * @function    twitterAction()
     * @description This function gets the users home-timeline
     *              and displays it in the widget.
     * @params      none
     * @return      none
     *
     */
    public function twitterAction()
    {
        $this->view->twitterData = $this->_twitter->getAccessToken();
    }

    public function twitterRefreshAction()
    {
        $this->_twitter->getOAuthToken();
    }

}

