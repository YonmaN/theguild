<?php

namespace Application;

use GuildUser\Mapper;

class Module implements \Zend\ModuleManager\Feature\ControllerProviderInterface, \Zend\ModuleManager\Feature\BootstrapListenerInterface, \Zend\ModuleManager\Feature\InitProviderInterface, \Zend\ModuleManager\Feature\ServiceProviderInterface
{
	public static $options;
        
        public function getControllerConfig() {
            return array(
                'factories' => array(
                    'index' => function ($sm) {
                        $controller = new \Application\Controller\IndexController();
                        $controller->setProfileMapper($sm->getServiceLocator()->get('GuildUser\Mapper\ProfileMapper'));
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
			'factories' => array(
//                            'zfcuser_user_mapper' => function ($sm) {
//                                    $adapter = $sm->get('zfcuser_zend_db_adapter');
//                                    $tg = new \Zend\Db\TableGateway\TableGateway('user', $adapter);
//                                    return new \GuildUser\Model\UserMapper($tg);
//                            },
                            'Zend\View\Strategy\JsonStrategy' => function($sm) {
                                    return new \Zend\View\Strategy\JsonStrategy(new \Zend\View\Renderer\JsonRenderer());
                            },
                            'GuildUser\Mapper\User' => function($sm) {
                                $baseUserMapper = $sm->get('zfcuser_user_mapper');
                                return \GuildUser\Mapper\User::fromZfcUser($baseUserMapper);
                            },
                            'GuildUser\Model\GameMapper' => function ($sm) {
                                $gameMapper = new \GuildUser\Model\GameMapper();
                                return $gameMapper;
                            },
                            'GuildUser\Mapper\ProfileMapper' => function ($sm) {
                                $profileMapper = new \GuildUser\Mapper\ProfileMapper();
                                
                                $profileMapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                                $profileMapper->setEntityPrototype(new Mapper\Profile());
                                $profileMapper->setHydrator(new Mapper\ProfileHydrator);
                                
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
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'GuildUser' => __DIR__ . '/src/GuildUser',
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
