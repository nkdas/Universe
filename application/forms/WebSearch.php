<?php

require_once 'Decorator.php';

class Application_Form_WebSearch extends Zend_Form
{

    public function init()
    {
        $this->setName('webSearch');

        $inputDecorator = new InputDecorator();
        $searchBox = new Zend_Form_Element_Text(
            'searchBox', array(
                'label' => 'Search the web',
                'type' => 'text',
                'placeholder' => 'Search the web',
                'decorators' => array($inputDecorator),
            )
        );

        $searchButtonDecorator = new SearchButtonDecorator();
        $googleButton = new Zend_Form_Element_Submit(
            'googleButton', array(
                'type' => 'submit',
                'decorators' => array($searchButtonDecorator),
            )
        );

        $bingButton = new Zend_Form_Element_Submit(
            'bingButton', array(
                'type' => 'submit',
                'decorators' => array($searchButtonDecorator),
            )
        );

        $yahooButton = new Zend_Form_Element_Submit(
            'yahooButton', array(
                'type' => 'submit',
                'decorators' => array($searchButtonDecorator),
            )
        );

        $wikipediaButton = new Zend_Form_Element_Submit(
            'wikipediaButton', array(
                'type' => 'submit',
                'decorators' => array($searchButtonDecorator),
            )
        );

        $this->addElements(array($searchBox, $googleButton, $bingButton, $yahooButton, $wikipediaButton));
    }

}
