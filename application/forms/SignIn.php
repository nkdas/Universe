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
        $email->setRequired(true)
            ->addValidator(
                'notEmpty',
                false,
                array(
                    'messages' => array(
                        'isEmpty' => 'Email Id cannot be blank'
                    )
                )
            )
            ->addValidator(
                'EmailAddress',
                false,
                array(
                    'messages' => array(
                        'emailAddressInvalidFormat' => 'Email Id is invalid'
                    )
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
        $password->setRequired(true)
            ->addValidator(
                'notEmpty',
                false,
                array(
                    'messages' => array(
                        'isEmpty' => 'Password cannot be blank'
                    )
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
