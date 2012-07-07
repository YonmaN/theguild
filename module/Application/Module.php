<?php

namespace Application;

use Zend\ModuleManager\ModuleManager as Manager,
    Zend\EventManager\StaticEventManager;

class Module implements \Zend\ModuleManager\Feature\BootstrapListenerInterface, \Zend\ModuleManager\Feature\InitProviderInterface, \Zend\ModuleManager\Feature\ServiceProviderInterface
{
	public static $options;
	
	public function getServiceConfiguration() {
		return array(
			'factories' => array(
				'zfcuser_user_mapper' => function ($sm) {
						$adapter = $sm->get('zfcuser_zend_db_adapter');
						$tg = new \Zend\Db\TableGateway\TableGateway('user', $adapter);
						return new \GuildUser\Model\UserMapper($tg);
					},
				'Zend\View\Strategy\JsonStrategy' => function($sm) {
					return new \Zend\View\Strategy\JsonStrategy(new \Zend\View\Renderer\JsonRenderer());
				}
			),
		);
	}
	
	public function init($manager = null) {
		$manager->events()->attach('loadModules.post', array($this, 'modulesLoaded'));
		date_default_timezone_set(@date_default_timezone_get());
	}
	
	public function onBootstrap(\Zend\EventManager\Event $e) {
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
		$app->events()->attach('route', function(\Zend\Mvc\MvcEvent $e){
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
