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
        $firstName->setRequired(true)
            ->addValidator(
                'notEmpty',
                false,
                array(
                    'messages' => array(
                        'isEmpty' => 'First name cannot be blank'
                    )
                )
            )
            ->addValidator(
                'Alpha',
                false,
                array('messages' => array(
                    'notAlpha' => 'Only letters are allowed as First name',
                    'alphaStringEmpty' => ''
                ))
            );

        $lastName = new Zend_Form_Element_Text(
            'lastName', array(
                'type' => 'text',
                'placeholder' => 'Last Name',
                'required' => 'true',
                'decorators' => array($inputDecorator),
            )
        );
        $lastName->setRequired(true)
            ->addValidator(
                'notEmpty',
                false,
                array(
                    'messages' => array(
                        'isEmpty' => 'Last name cannot be blank'
                    )
                )
            )
            ->addValidator(
                'Alpha',
                false,
                array('messages' => array(
                    'notAlpha' => 'Only letters are allowed as Last name',
                    'alphaStringEmpty' => ''
                ))
            );

        $email = new Zend_Form_Element_Text(
            'email', array(
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
                        'emailAddressInvalidFormat' => 'The Email Id is invalid'
                    )
                )
            );

        $password = new Zend_Form_Element_Password(
            'password', array(
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

        $reTypePassword = new Zend_Form_Element_Password(
            'reTypePassword', array(
                'type' => 'password',
                'placeholder' => 'Re-enter Password',
                'decorators' => array($inputDecorator),
            )
        );
        $reTypePassword->setRequired(true)
            ->addValidator(
                'notEmpty',
                false,
                array(
                    'messages' => array(
                        'isEmpty' => 'Password confirmation cannot be blank'
                    )
                )
            )
            ->addValidator(
                'Identical',
                TRUE,
                array('token' => 'password',
                    'messages' => array(
                        'notSame' => 'Password and confirm password must be same',
                        'missingToken' => 'Password and confirm password must be same'
                    )
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
