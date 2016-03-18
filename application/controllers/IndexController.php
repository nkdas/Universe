<?php

class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {

        // Displays the SignIn form in index view
        $signInForm = new Application_Form_SignIn();
        $signInForm->submit->setLabel('Signin');
        $this->view->signinForm = $signInForm;

        // Displays the SignUp form in index view
        $signUpForm = new Application_Form_SignUp();
        $signUpForm->submit->setLabel('Signup');
        $this->view->signupForm = $signUpForm;

        if ($formData = $this->getRequest()->getPost()) {
            if('Sign in' == ($this->getRequest()->getPost()['submit']) ) {
                $this->signIn();
            } else {
                try{
                    if($signUpForm->isValid($formData)){
                        $this->signUp();
                    }
                    else{

                    }
                } catch (Exception $ex) {

                }

            }
        }
    }

    public function signIn()
    {
        echo 'Hello';
    }

    public function signUp()
    {
        echo 'Hi';
    }
}
