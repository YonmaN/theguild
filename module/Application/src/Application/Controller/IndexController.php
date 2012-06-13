<?php

namespace Application\Controller;

use ZfcUser\Model\UserMetaMapper, Zend\Form\Form;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel;

class IndexController extends ActionController
{
	private $userMapper;
	private $gameMapper;
	private $profileMapper;
    public function indexAction()
    {
        return new ViewModel();
    }

    public function searchAction() {
    	$basePath = $this->getRequest()->getBaseUrl();
    	$view = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/ToolTip.css");
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/tooltip-content.css");
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/search.css");
    	
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools-core-1.4.5-full-compat.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools-more-1.4.0.1.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/ToolTip.js");
    	
    	$users = $this->getUserMapper()->findAll();
    	$ids = array_map(function ($user) {return $user->getUserId();}, $users);
    	$profiles = $this->getProfileMapper()->findByUserIds($ids);
    	return new ViewModel(array('users' => $users, 'profiles' => $profiles));
    }

    public function sheetAction() {
    	$id = $this->getRequest()->query()->get('id');
    	$view = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
    	$basePath = $this->getRequest()->getBaseUrl();
    	 
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/sheet.css");
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/ToolTip.css");
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/tooltip-content.css");
    	 
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools-core-1.4.5-full-compat.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools-more-1.4.0.1.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/ToolTip.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/Carousel.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/Carousel.Extra.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/PeriodicalExecuter.js");
    	$row = $this->getUserMapper()->findById($id);
     	$games = $this->getGameMapper()->findByUserId($id);
    	$profile = $this->getProfileMapper()->findByUserId($id);
    	return new ViewModel(array(
			'row' => $row,
        	'games' => $games,
        	'profile' => $profile
		));
    }
    
    /**
     * @return \GuildUser\Model\UserMapper
     */
    public function getUserMapper() {
    	return $this->userMapper;
    }
    
    /**
     * @param \GuildUser\Model\UserMapper $userMapper
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
    
    /**
     * @return \GuildUser\Model\ProfileMapper
     */
    public function getProfileMapper() {
    	return $this->profileMapper;
    }
    
    public function setProfileMapper($profileMapper) {
    	$this->profileMapper = $profileMapper;
    }
}

