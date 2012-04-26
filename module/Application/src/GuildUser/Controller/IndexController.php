<?php

namespace GuildUser\Controller;

use ZfcUser\Model\UserMetaMapper, Zend\Form\Form;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel;

class IndexController extends ActionController
{
	private $userMapper;
	private $userMetaMapper;
	private $gameMapper;
	private $profileMapper;
    
    public function indexAction() {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute('zfcuser/login');
    	}
    	
    	$basePath = $this->getRequest()->getBaseUrl();
    	$view = $this->getLocator()->get('Zend\View\Renderer\PhpRenderer');
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools-core-1.4.5-full-compat.js");
    	$view->plugin('headScript')->appendFile("$basePath/js/mootools-more-1.4.0.1.js");
    	$view->headScript()->appendFile("$basePath/js/slider.js");
    	$view->headScript()->appendFile("$basePath/js/TabPane.js");
    	
    	$view->headLink()->appendStylesheet("$basePath/css/slider.css");
    	$view->headLink()->appendStylesheet("$basePath/css/tooltip-content.css");
    	$view->headLink()->appendStylesheet("$basePath/css/tabs.css");
    	$view->headLink()->appendStylesheet("$basePath/css/myself.css");
    	
		$user = $this->zfcUserAuthentication()->getIdentity(); /* @var $user \ZfcUser\Model\User */
		
		$profileMapper = $this->getProfileMapper();
		$profile = $profileMapper->findByUserId($user->getUserId());
		
		$personal = $this->getDetailsForm();
		$personal->setIsArray(true);
		$personal->setName('personal');
		$personal->setAction($this->url()->fromRoute('default', array('controller' => 'guilduser', 'action' => 'details')));
		$personal->setAttrib('id', 'personal-form');
		
		$details = array(
			'full_name' => $profile->getName(),
			'display_name' => $user->getDisplayName(),
			'email' => $user->getEmail(),
			'lfg' => $profile->getLfg() === 'true' ? 'Yes' : 'No',
			'gender' => ucwords($profile->getGender())
		);
		$personal->setMethod('post');
		$personal->populate($details);
		
		$attributes = $this->getAttributesForm();
		$attributes->setIsArray(true);
		$attributes->setName('attributes');
		$attributes->setAction($this->url()->fromRoute('default', array('controller' => 'guilduser', 'action' => 'attributes')));
		$attributes->setAttrib('id', 'attributes-form');
		
		$attributes->populate($profile->toArray());
		
    	return new ViewModel(array('user' => $user, 'personal' => $personal, 'profile' => $profile, 'attributesForm' => $attributes));
    }
    
	public function attributesAction() {
		if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return new \Zend\View\Model\JsonModel(array('success' => false));
    	}
		
		$attributes = $this->getAttributesForm();
		$attributes->setIsArray(true);
		$attributes->setName('attributes');
		
		if ($this->getRequest()->isPost()) {
			$valid = $attributes->isValid($this->getRequest()->post()->toArray());
			$payload = array('success' => $valid);
			if ($valid) {
				$user = $this->zfcUserAuthentication()->getIdentity(); /* @var $user \ZfcUser\Model\User */
				$profileMapper = $this->getProfileMapper();
				$profile = $profileMapper->findByUserId($user->getUserId()); /* @var $profile \GuildUser\Model\Profile */
				$profile->setHumour($attributes->getValue('humour'));
				$profileMapper->persist($profile);
			} else {
				$payload['errors'] = $attributes->getMessages();
			}
		} else {
			$payload = array('success' => false);
		}
		$viewModel = new \Zend\View\Model\JsonModel($payload);
		
		$viewModel->setTerminal(true);
		return $viewModel;
	}
    
	public function detailsAction() {
		if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return new \Zend\View\Model\JsonModel(array('success' => false));
    	}
		
		$personal = $this->getDetailsForm();
		$personal->setIsArray(true);
		$personal->setName('personal');
		
		if ($this->getRequest()->isPost()) {
			$valid = $personal->isValid($this->getRequest()->post()->toArray());
			$payload = array('success' => $valid);
			if ($valid) {
				$user = $this->zfcUserAuthentication()->getIdentity(); /* @var $user \ZfcUser\Model\User */
				$userModel = $this->getUserMapper();
				$userModel->persist(
							$user
								->setDisplayName($personal->getValue('display_name'))
								->setEmail($personal->getValue('email'))
						);
				
				
				$profileMapper = $this->getProfileMapper();
				$profile = $profileMapper->findByUserId($user->getUserId()); /* @var $profile \GuildUser\Model\Profile */
				
				$profile->setLfg($personal->getValue('lfg'));
				$profile->setGender($personal->getValue('gender'));
				$profile->setName($personal->getValue('full_name'));
				$profile->setBio($personal->getValue('bio'));
				
				$profileMapper->persist($profile);
			} else {
				$payload['errors'] = $personal->getMessages();
			}
		} else {
			$payload = array('success' => false);
		}
		$viewModel = new \Zend\View\Model\JsonModel($payload);
		
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	
	/**
	 * @return \Zend\Form\Form 
	 */
	private function getDetailsForm() {
		return new Form(array(
				'elements' => array(
					'bio' => array('type' => 'textarea', 'options' => array('value' => 'סיפור רקע ו-Fluff','rows' => 15,)),
					'full_name' => array('type' => 'text','options' => array('label' => 'שם מלא')),
					'display_name' => array('type' => 'text','options' => array('label' => 'שם תצוגה')),
					'email' => array('type' => 'text','options' => array('label' => 'דוא"ל')),
					'lfg' => array('type' => 'radio','options' => array('label' => 'מחפש?', 'multiOptions' => array('Yes' => 'כן, אני מחפש קבוצה', 'No' => 'לא, תעזבו אותי בשקט'))),
					'gender' => array('type' => 'radio','options' => array('label' => 'מין', 'multiOptions' => array('Male' => 'שחקן', 'Female' => 'שחקנית', 'Other' => 'משהו אחר או לא ניתן להגדרה'))),
					'personal-submit' => array('type' => 'submit', 'options' => array('label' => 'שמור פרטים אישיים'))
				),
			)
		);
	}
	
	/**
	 * @return \Zend\Form\Form 
	 */
	private function getAttributesForm() {
		return new Form(array(
				'elements' => array(
					'humour' => array('type' => 'hidden','options' => array('label' => '', 'class' => 'input-mini')),
					'teamplay' => array('type' => 'hidden','options' => array('label' => '', 'class' => 'input-mini')),
					'leadership' => array('type' => 'text','options' => array('label' => 'מנהיגות', 'class' => 'input-mini')),
					'mobility' => array('type' => 'text','options' => array('label' => 'ניידות', 'class' => 'input-mini')),
					'focus' => array('type' => 'text','options' => array('label' => 'מיקוד', 'class' => 'input-mini')),
					'hospitality' => array('type' => 'text','options' => array('label' => 'אירוח', 'class' => 'input-mini')),
					'strictness' => array('type' => 'text','options' => array('label' => 'קפדנות', 'class' => 'input-mini')),
					'attributes-submit' => array('type' => 'submit', 'options' => array('label' => 'שמור תכונות'))
				),
			)
		);
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
     * @return \GuildUser\Model\UserMetaMapper
     */
    public function getUserMetaMapper() {
    	return $this->userMetaMapper;
    }
    
    /**
     * @param \GuildUser\Model\UserMetaMapper $userMapper
     */
    public function setUserMetaMapper($userMetaMapper) {
    	$this->userMetaMapper = $userMetaMapper;
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

