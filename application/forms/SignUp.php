<?php

require_once 'SignIn.php';

class Application_Form_SignUp extends Zend_Form
{

    public function init()
    {
        $this->setName('signUp');


        $decorator = new My_Decorator_SimpleInput();

        $firstName = new Zend_Form_Element_Text(
            'firstName', array(
                'type' => 'text',
                'placeholder' => 'First Name',
                'required' => 'true',
                'decorators' => array($decorator),
            )
        );

        $firstName->setRequired('true');

        $lastName = new Zend_Form_Element_Text(
            'lastName', array(
                'type' => 'text',
                'placeholder' => 'Last Name',
                'required' => 'true',
                'decorators' => array($decorator),
            )
        );

        $email = new Zend_Form_Element_Text(
            'email', array(
                'type' => 'text',
                'placeholder' => 'Email',
                'decorators' => array($decorator),
            )
        );

        $password = new Zend_Form_Element_Password(
            'password', array(
                'type' => 'password',
                'placeholder' => 'Password',
                'decorators' => array($decorator),
            )
        );

        $reTypePassword = new Zend_Form_Element_Password(
            'reTypePassword', array(
                'type' => 'password',
                'placeholder' => 'Re-enter Password',
                'decorators' => array($decorator),
            )
        );

        $buttonDecorator = new My_Decorator_SimpleButton();
        $submit = new Zend_Form_Element_Submit(
            'submit', array(
                'value' => 'Sign up',
                'type' => 'submit',
                'decorators' => array($buttonDecorator),
            )
        );

        $this->addElements(array($firstName, $lastName, $email, $password, $reTypePassword, $submit));
    }

}

