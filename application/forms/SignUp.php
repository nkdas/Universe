<?php

require_once 'Decorator.php';

class Application_Form_SignUp extends Zend_Form
{

    public function init()
    {
        $this->setName('signUp');

        $inputDecorator = new InputDecorator();

        $firstName = new Zend_Form_Element_Text(
            'firstName', array(
                'type' => 'text',
                'placeholder' => 'First Name',
                'required' => 'true',
                'decorators' => array($inputDecorator),
            )
        );

        $firstName->setRequired('true');

        $lastName = new Zend_Form_Element_Text(
            'lastName', array(
                'type' => 'text',
                'placeholder' => 'Last Name',
                'required' => 'true',
                'decorators' => array($inputDecorator),
            )
        );

        $email = new Zend_Form_Element_Text(
            'email', array(
                'type' => 'text',
                'placeholder' => 'Email',
                'decorators' => array($inputDecorator),
            )
        );

        $password = new Zend_Form_Element_Password(
            'password', array(
                'type' => 'password',
                'placeholder' => 'Password',
                'decorators' => array($inputDecorator),
            )
        );

        $reTypePassword = new Zend_Form_Element_Password(
            'reTypePassword', array(
                'type' => 'password',
                'placeholder' => 'Re-enter Password',
                'decorators' => array($inputDecorator),
            )
        );

        $buttonDecorator = new ButtonDecorator();
        $submit = new Zend_Form_Element_Submit(
            'submit', array(
                'id' => 'signUpButton',
                'value' => 'Sign up',
                'type' => 'submit',
                'decorators' => array($buttonDecorator),
            )
        );

        $this->addElements(array($firstName, $lastName, $email, $password, $reTypePassword, $submit));
    }

}
