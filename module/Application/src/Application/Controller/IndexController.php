<?php

namespace Application\Controller;

use ZfcUser\Model\UserMetaMapper, Zend\Form\Form;

use Zend\Mvc\Controller\AbstractActionController as ActionController,
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
    	
		$tooltipMapper = $this->getServiceLocator()->get('GuildUser\Attribute\TooltipMapper'); /* @var $tooltipMapper \GuildUser\Attribute\TooltipMapper */
		$attributeTooltips = $tooltipMapper->findAllTooltips();
		
    	$profiles = $this->getProfileMapper()->findAll();
    	return new ViewModel(array('profiles' => $profiles, 'tooltips' => $attributeTooltips));
    }

    public function sheetAction() {
    	$id = $this->getRequest()->getQuery()->get('id');
    	$view = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
    	$basePath = $this->getRequest()->getBaseUrl();
    	 
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/sheet.css");
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/ToolTip.css");
    	$view->plugin('headLink')->appendStylesheet("$basePath/css/tooltip-content.css");
    	 
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools-core-1.4.5-full-compat.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools-more-1.4.0.1.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/ToolTip.js");
        
    	$row = $this->getUserMapper()->findById($id);
     	$games = $this->getGameMapper()->findByUserId($id);
    	$profile = $this->getProfileMapper()->findByUserId($id);
		
		$reader = simplexml_load_file(getcwd().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'tooltips.xml');
		$attributesContent = array();
		foreach($reader->xpath('tooltip[@type="attribute"]') as $tooltip) {
			$attributesContent[(string)$tooltip['id']] = array('name' => (string)$tooltip->name, 'headline' => (string)$tooltip->headline);
			$attributesContent[(string)$tooltip['id']]['scores'] = array();
			$scores = current((array)$tooltip->scores);
			foreach ($scores as $score) {
				$attributesContent[(string)$tooltip['id']]['scores'][(string)$score['value']] = array('quote' => (string)$score->quote, 'text' => (string)$score->text);
			}
		}
		
		
    	return new ViewModel(array(
			'tooltips' => $attributesContent,
			'row' => $row,
        	'games' => $games,
        	'profile' => $profile
		));
    }
    
    /**
     * @return \GuildUser\Mapper\User
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

