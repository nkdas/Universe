<?php

class My_Decorator_SimpleInput extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<div class="form-group">
        <input id="%s" name="%s" type="%s" class="form-control" placeholder="%s"/></div>';

    public function render($content)
    {
        $element = $this->getElement();
        $name = htmlentities($element->getFullyQualifiedName());
        $id = htmlentities($element->getId());
        $type = htmlentities($element->getAttrib('type'));
        $placeholder = htmlentities($element->getAttrib('placeholder'));

        $markup = sprintf($this->_format, $id, $name, $type, $placeholder);

        return $markup;
    }
}

class My_Decorator_SimpleButton extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<div class="form-group">
        <input id="%s" name="%s" type="%s" class="btn btn-primary" value="%s"/></div>';

    public function render($content)
    {
        $element = $this->getElement();
        $name = htmlentities($element->getFullyQualifiedName());
        $id = htmlentities($element->getId());
        $value = htmlentities($element->getValue());
        $type = htmlentities($element->getAttrib('type'));

        $markup = sprintf($this->_format, $id, $name, $type, $value);

        return $markup;
    }
}

class Application_Form_SignIn extends Zend_Form
{

    public function init()
    {
        $this->setName('login');

        $decorator = new My_Decorator_SimpleInput();
        $email = new Zend_Form_Element(
            'email', array(
                'label' => 'Email',
                'type' => 'text',
                'placeholder' => 'Email',
                'decorators' => array($decorator),
            )
        );

        $password = new Zend_Form_Element_Password(
            'password', array(
                'label' => 'Password',
                'type' => 'password',
                'placeholder' => 'Password',
                'decorators' => array($decorator),
            )
        );

        $buttonDecorator = new My_Decorator_SimpleButton();
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
