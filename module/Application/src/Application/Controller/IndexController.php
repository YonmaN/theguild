<?php

namespace Application\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel;

class IndexController extends ActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function myselfAction() {
    	return new ViewModel();
    }

    public function searchAction() {
    	return new ViewModel();
    }

    public function sheetAction() {
    	$id = $this->getRequest()->query()->get('id');
    	$view = $this->getLocator()->get('view');
    	$basePath = $this->getRequest()->getBaseUrl();
    	 
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/sheet.css");
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/ToolTip.css");
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/tooltip-content.css");
    	 
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools-more-1.3.1.1.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/ToolTip.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/Carousel.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/Carousel.Extra.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/PeriodicalExecuter.js");
    	
    	return new ViewModel(array(
			'row' => array(),
        	'games' => array(),
        	'profile' => array()
		));
    }
}
