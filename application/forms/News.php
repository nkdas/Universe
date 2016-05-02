<?php

require_once 'Decorator.php';

class Application_Form_News extends Zend_Form
{

    public function init()
    {
        $this->setName('News')
            ->setAttrib('class', 'form-vertical');
        $multipleFeeds = array(
            'cnn_latest' => 'Most Recent',
            'edition' => 'Top Stories',
            'World'=>array(
                'edition_world' =>'World',
                'edition_asia' =>'Asia',
                'edition_americas'=>'Americas',
                ),
            'Sports'=>array(
                'edition_sport'=>'World Sport',
                'edition_football'=>'Football',
                'edition_golf'=>'Golf',
                'edition_motorsport'=>'Motorsport',
                'edition_tennis'=>'Tennis',
            ),
            'Business'=>array(
                'money_news_international'=>'Money',
                'money_markets'=>'market',
                'money_smbusiness'=>'smbusiness',
            ),
             'Others'=>array(
                 'edition_travel'=>'Travel',
                 'edition_technology' => 'Technology',
                 'edition_space' => 'Science & Space',
                 'edition_entertainment' => 'entertainment',
             )
        );
        $gender = new Zend_Form_Element_Select('News');
        $gender->setMultiOptions($multipleFeeds)
            ->setAttrib('class', 'form-control form-group selectpicker')
            ->setAttrib('id', 'searchFeed')
            ->setLabel('Select a category')
            ->setAttrib("onclick", "feed();");

        $this->addElement($gender);
    }

}
