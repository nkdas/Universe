<?php

require_once 'Decorator.php';

class Application_Form_SignIn extends Zend_Form
{

    public function init()
    {
        $this->setName('login');

        $inputDecorator = new InputDecorator();
        $email = new Zend_Form_Element(
            'email', array(
                'label' => 'Email',
                'type' => 'text',
                'placeholder' => 'Email',
                'decorators' => array($inputDecorator),
            )
        );

        $password = new Zend_Form_Element_Password(
            'password', array(
                'label' => 'Password',
                'type' => 'password',
                'placeholder' => 'Password',
                'decorators' => array($inputDecorator),
            )
        );

        $buttonDecorator = new ButtonDecorator();
        $submit = new Zend_Form_Element_Submit(
            'submit', array(
                'id' => 'signInButton',
                'value' => 'Sign in',
                'type' => 'submit',
                'decorators' => array($buttonDecorator),
            )
        );

        $this->addElements(array($email, $password, $submit));
    }
}
