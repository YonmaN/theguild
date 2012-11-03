<?php

namespace Application;

use GuildUser\Mapper;

class Module implements \Zend\ModuleManager\Feature\ViewHelperProviderInterface, \Zend\ModuleManager\Feature\ControllerProviderInterface, \Zend\ModuleManager\Feature\BootstrapListenerInterface, \Zend\ModuleManager\Feature\InitProviderInterface, \Zend\ModuleManager\Feature\ServiceProviderInterface
{
	public static $options;
        
        public function getViewHelperConfig() {
            return array(
                'invokables' => array(
                    'slider' => 'MooTools\View\Helper\Slider',
                    'gameIcon' => 'Game\View\Helper\GameIcon'
                )
            );
        }
        
        public function getControllerConfig() {
            return array(
                'factories' => array(
                    'index' => function ($sm) {
                        $controller = new \Application\Controller\IndexController();
                        $controller->setProfileMapper($sm->getServiceLocator()->get('GuildUser\Mapper\ProfileMapper'));
                        $controller->setGameMapper($sm->getServiceLocator()->get('GuildUser\Mapper\Game'));
                        $controller->setUserMapper($sm->getServiceLocator()->get('GuildUser\Mapper\User'));
                        return $controller;
                    },
                    'guilduser' => function ($sm) {
                        $controller = new \GuildUser\Controller\IndexController();
                        $controller->setUserMapper($sm->getServiceLocator()->get('GuildUser\Model\UserMapper'));
                        $controller->setGameMapper($sm->getServiceLocator()->get('GuildUser\Model\GameMapper'));
                        $controller->setProfileMapper($sm->getServiceLocator()->get('GuildUser\Model\ProfileMapper'));
                        return $controller;
                    }
                ),
            );
        }
        
	public function getServiceConfig() {
		return array(
                        'invokables' => array(
                            
                        ),
			'factories' => array(
                            'Zend\View\Strategy\JsonStrategy' => function($sm) {
                                    return new \Zend\View\Strategy\JsonStrategy(new \Zend\View\Renderer\JsonRenderer());
                            },
                            'GuildUser\Mapper\User' => function($sm) {
                                $baseUserMapper = $sm->get('zfcuser_user_mapper');
                                return \GuildUser\Mapper\User::fromZfcUser($baseUserMapper);
                            },
                            'GuildUser\Mapper\Game' => function ($sm) {
                                $gameMapper = new \GuildUser\Mapper\Game();
                                $gameMapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                                $gameMapper->setEntityPrototype(new Mapper\UserGame());
                                $gameMapper->setHydrator(new \Zend\Stdlib\Hydrator\ClassMethods);
                                
                                return $gameMapper;
                            },
                            'GuildUser\Mapper\ProfileMapper' => function ($sm) {
                                $profileMapper = new \GuildUser\Mapper\ProfileMapper();
                                
                                $profileMapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                                $profileMapper->setEntityPrototype(new Mapper\Profile());
                                $profileMapper->setHydrator(new \Zend\Stdlib\Hydrator\ClassMethods);
                                
                                return $profileMapper;
                            }
			),
		);
	}
	
	public function init(\Zend\ModuleManager\ModuleManagerInterface $manager = null) {
		$manager->getEventManager()->attach('loadModules.post', array($this, 'modulesLoaded'));
		date_default_timezone_set(@date_default_timezone_get());
	}
	
	public function onBootstrap(\Zend\EventManager\EventInterface $e) {
            $app = $e->getApplication();
            $sm = $app->getServiceManager();
            $sm->addAbstractFactory(new \Zend\ServiceManager\Di\DiAbstractServiceFactory($sm->get('Di')));
		$this->initializeSessionControl($e);
	}

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'GuildUser' => __DIR__ . '/src/GuildUser',
                    'Game' => __DIR__ . '/src/Game',
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
	public function initializeSessionControl(\Zend\Mvc\MvcEvent $e) {
		$app = $e->getApplication();
		$app->getEventManager()->attach('route', function(\Zend\Mvc\MvcEvent $e){
			$routeMatch = $e->getRouteMatch(); /* @var $routeMatch \Zend\Mvc\Router\RouteMatch */
			$controller = $routeMatch->getParam('controller');
			if ($controller == 'guilduser') {
				$authService = new \Zend\Authentication\AuthenticationService;
				if (! $authService->hasIdentity()) {
					$routeMatch->setParam('controller', 'zfcuser');
					$routeMatch->setParam('action', 'login');
				}
			}
		});
	}
	
    public function modulesLoaded($e)
    {
        $config = $e->getConfigListener()->getMergedConfig();
        static::$options = $config;
    }

    /**
     * @TODO: Come up with a better way of handling module settings/options
     */
    public static function getOption($option)
    {
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }
}
