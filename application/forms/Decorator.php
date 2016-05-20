<?php
class InputDecorator extends Zend_Form_Decorator_Abstract
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

class ButtonDecorator extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<input id="%s" name="%s" type="%s" class="btn btn-primary" value="%s"/>';

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

class SearchButtonDecorator extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<input id="%s" name="%s" type="%s"
        class="btn btn-primary searchButtons card-shadow" value="%s"/>';

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
