<?php

namespace GuildUser\Controller;

use ZfcUser\Model\UserMetaMapper, Zend\Form\Form;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
	GuildUser\Model\UserGame;

class IndexController extends ActionController
{
	private $userMapper;
	private $userMetaMapper;
	private $userGameMapper;
	private $gameMapper;
	private $profileMapper;
    
    public function indexAction() {
    	
    	$basePath = $this->getRequest()->getBaseUrl();
    	$view = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
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
		$personal->setName('personal');
		$personal->setAttribute('action', $this->url()->fromRoute('default', array('controller' => 'guilduser', 'action' => 'details')));
		$personal->setAttribute('id', 'personal-form');
		
		$details = array(
			'full_name' => $profile->getName(),
			'display_name' => $user->getDisplayName(),
			'email' => $user->getEmail(),
			'lfg' => $profile->getLfg(),
			'gender' => ucwords($profile->getGender())
		);
		$personal->setAttribute('method', 'post');
		$personal->setData($details);
		
		$attributes = $this->getAttributesForm();
		$attributes->setName('attributes');
		$attributes->setAttribute('action', $this->url()->fromRoute('default', array('controller' => 'guilduser', 'action' => 'attributes')));
		$attributes->setAttribute('id', 'attributes-form');
		
		$attributes->setData($profile->toArray());
		
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
		
		$games = $this->getGameMapper()->findAllGames();
		$userGameMapper = $this->getServiceLocator()->get('GuildUser\Model\GameMapper');
		$userGames = $userGameMapper->findByUserId($user->getUserId());

		$skillsForm = $this->getSkillsForm();
		$skillsForm->setName('skills');
		$skillsForm->setAttribute('action', $this->url()->fromRoute('default', array('controller' => 'guilduser', 'action' => 'setgame')));
		$skillsForm->setAttribute('id', 'skills-form');
		
    	return new ViewModel(array('assignedGames' => $userGames, 'games' => $games, 'user' => $user,
			'personal' => $personal, 'profile' => $profile, 'attributesForm' => $attributes, 'tooltips' => $attributesContent,
			'skillsForm' => $skillsForm));
    } 
    
	public function setgamesAction() {
		$user = $this->zfcUserAuthentication()->getIdentity(); /* @var $user \ZfcUser\Model\User */
		
		$params = $this->getRequest()->post();
		$userGameMapper = $this->getServiceLocator()->get('GuildUser\Model\GameMapper');
		
		foreach ($params['games'] as $gameId => $enable) {
			$userGame = $userGameMapper->findUserGame($user->getUserId(),$gameId);
			if (! $userGame->getUserId()) {
				$userGame = UserGame::fromArray(array(
					'userId' => $user->getUserId(), 'gameId' => $gameId
				));
			}

			if (isset($enable)) {
				$userGame->setEnabled(intval($enable));
			}

			$userGameMapper->persist($userGame);
			
		}
		
		return $this->getResponse();
	}
	
	public function setgameAction() {
		$user = $this->zfcUserAuthentication()->getIdentity(); /* @var $user \ZfcUser\Model\User */
		
		$params = $this->getRequest()->post();
		if (isset($params['skills'])) {
			$params = $params['skills'];
		}
		$userGameMapper = $this->getServiceLocator()->get('GuildUser\Model\GameMapper');
		
		$userGame = $userGameMapper->findUserGame($user->getUserId(),$params['gameId']);
		if (! $userGame->getUserId()) {
			$userGame = UserGame::fromArray(array(
				'userId' => $user->getUserId(), 'gameId' => $params['gameId']
			));
		}
		
		if (isset($params['enable'])) {
			$userGame->setEnabled(intval($params['enable']));
		}

		if (isset($params['xp'])) {
			$userGame->setXp(intval($params['xp']));
		}

		if (isset($params['gm'])) {
			$userGame->setGm(intval($params['gm']));
		}

		if (isset($params['comments'])) {
			$striptags = new \Zend\Filter\StripTags();
			$userGame->setComments($striptags->filter($params['comments']));
		}

		if (isset($params['xp'])) {
			$userGame->setXp(intval($params['xp']));
		}
		
		$userGameMapper->persist($userGame);
		return $this->getResponse();
	}
	
	public function getusergameAction() {
		$user = $this->zfcUserAuthentication()->getIdentity(); /* @var $user \ZfcUser\Model\User */
		
		$params = $this->getRequest()->query();
		
		$userGameMapper = $this->getServiceLocator()->get('GuildUser\Model\GameMapper');
			$userGame = $userGameMapper->findUserGame($user->getUserId(),$params['gameId']);
		if (! $userGame) {
			$userGame = \GuildUser\Model\UserGame::fromArray(array());
		}
		
		$view = new \Zend\View\Model\JsonModel();
		$view->setTerminal(true);
		$view->setVariables(array('success' => true, 'userGame' => $userGame->toArray()));
		
		return $view;
	}
	
	public function attributesAction() {
		
		$attributes = $this->getAttributesForm();
		$attributes->prepare();
		$attributes->setName('attributes');
		
		if ($this->getRequest()->isPost()) {
			$attributes->setData($this->getRequest()->post()->toArray());
			$valid = $attributes->isValid();
			$payload = array('success' => $valid);
			if ($valid) {
				$user = $this->zfcUserAuthentication()->getIdentity(); /* @var $user \ZfcUser\Model\User */
				$profileMapper = $this->getProfileMapper();
				$profile = $profileMapper->findByUserId($user->getUserId()); /* @var $profile \GuildUser\Model\Profile */
				$profile->setFromArray($this->getRequest()->post()->toArray());
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
		$personal = $this->getDetailsForm();
		$personal->setName('personal');
		
		if ($this->getRequest()->isPost()) {
			$post = $this->getRequest()->post()->toArray();
			$personal->prepare();
			$personal->setData($post);
			$valid = $personal->isValid();
			$payload = array('success' => $valid);
			if ($valid) {
				$user = $this->zfcUserAuthentication()->getIdentity(); /* @var $user \ZfcUser\Model\User */
				$userModel = $this->getUserMapper();
				$userModel->persist(
							$user
								->setDisplayName($post['display_name'])
								->setEmail($post['email'])
						);
				
				
				$profileMapper = $this->getProfileMapper();
				$profile = $profileMapper->findByUserId($user->getUserId()); /* @var $profile \GuildUser\Model\Profile */
				
				$profile->setLfg($post['lfg']);
				$profile->setGender($post['gender']);
				$profile->setName($post['full_name']);
				$profile->setBio($post['bio']);
				
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
		$factory = new \Zend\Form\Factory();
		return $factory->createForm(array(
				'input_filter' => array(),
				'elements' => array(
					array('spec' => array('name' => 'bio', 'attributes' => array('type' => 'textarea', 'value' => 'סיפור רקע ו-Fluff','rows' => 15,))),
					array('spec' => array('name' => 'full_name', 'attributes' => array('type' => 'text','label' => 'שם מלא'))),
					array('spec' => array('name' => 'display_name', 'attributes' => array('type' => 'text','label' => 'שם תצוגה'))),
					array('spec' => array('name' => 'email', 'attributes' => array('type' => 'text','label' => 'דוא"ל'))),
					array('spec' => array('name' => 'lfg', 'attributes' => array('type' => 'radio','label' => 'מחפש?', 'options' => array('כן, אני מחפש קבוצה' => 'Yes', 'לא, תעזבו אותי בשקט' => 'No')))),
					array('spec' => array('name' => 'gender', 'attributes' => array('type' => 'radio','label' => 'מין', 'options' => array('שחקן' => 'Male', 'שחקנית' => 'Female', 'משהו אחר או לא ניתן להגדרה' => 'Other')))),
					array('spec' => array('name' => 'personal-submit', 'attributes' => array('type' => 'submit', 'value' => 'שמור פרטים אישיים')))
				),
			)
		);
	}
	
	/**
	 * @return \Zend\Form\Form 
	 */
	private function getAttributesForm() {
		$factory = new \Zend\Form\Factory();
		return $factory->createForm(array(
				'input_filter' => array(),
				'elements' => array(
					array('spec' => array('name' => 'humour', 'attributes' => array('type' => 'hidden', 'label' => 'הומור', 'id' => 'attributes-humour'))),
					array('spec' => array('name' => 'teamplay', 'attributes' => array('type' => 'hidden', 'label' => 'שיתוף פעולה', 'id' => 'attributes-teamplay'))),
					array('spec' => array('name' => 'mobility', 'attributes' => array('type' => 'hidden', 'label' => 'ניידות', 'id' => 'attributes-mobility'))),
					array('spec' => array('name' => 'hospitality', 'attributes' => array('type' => 'hidden', 'label' => 'אירוח', 'id' => 'attributes-hospitality'))),
					array('spec' => array('name' => 'strictness', 'attributes' => array('type' => 'hidden', 'label' => 'קפדנות', 'id' => 'attributes-strictness'))),
					array('spec' => array('name' => 'attributes-submit',  'attributes' => array('type' => 'submit','value' => 'שמור תכונות', 'id' => 'attributes-attributessubmit')))
				),
			)
		);
	}
	
	/**
	 * @return \Zend\Form\Form 
	 */
	private function getSkillsForm() {
		$factory = new \Zend\Form\Factory();
		return $factory->createForm(array(
				'elements' => array(
					array('spec' => array('name' => 'gameId', 'attributes' => array('type' => 'hidden'))),
					array('spec' => array('name' => 'xp', 'attributes' => array('type' => 'hidden'))),
					array('spec' => array('name' => 'gm', 'attributes' => array('type' => 'hidden'))),
					array('spec' => array('name' => 'comments', 'attributes' => array('type' => 'textarea', 'label' => 'הערות'))),
					array('spec' => array('name' => 'submit', 'attributes' => array('type' => 'submit', 'label' => 'שמור')))
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
     * @return \GuildUser\Model\GameMapper
     */
    public function getUserGameMapper() {
    	return $this->userGameMapper;
    }
    
    public function setUserGameMapper($userGameMapper) {
    	$this->userGameMapper = $userGameMapper;
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

