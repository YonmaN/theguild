<?php

namespace Application\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel;

class IndexController extends ActionController
{
	private $userMapper;
	private $gameMapper;
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
    	$view = $this->getLocator()->get('Zend\View\Renderer\PhpRenderer');
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
    	$row = $this->getUserMapper()->findById($id);
     	$games = $this->getGameMapper()->findByUserId($id);
//     	$profile = $this->getProfileDbMapper()->getUserProfile($id);
    	return new ViewModel(array(
			'row' => $row,
        	'games' => $games,
        	'profile' => array()
		));
    }
    
    /**
     * @return \ZfcUser\Model\UserMapper
     */
    public function getUserMapper() {
    	return $this->userMapper;
    }
    
    /**
     * @param \ZfcUser\Model\UserMapper $userMapper
     */
    public function setUserMapper($userMapper) {
    	$this->userMapper = $userMapper;
    }
    
    /**
     * @return \Game\Model\GameMapper
     */
    public function getGameMapper() {
    	return $this->gameMapper;
    }
    
    public function setGameMapper($gameMapper) {
    	$this->gameMapper = $gameMapper;
    }
}
