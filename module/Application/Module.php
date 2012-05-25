<?php

namespace Application;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeSessionControl'), 99);
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
    
    public function initializeView($e)
    {
        $app          = $e->getParam('application');
        $basePath     = $app->getRequest()->getBasePath();
        $locator      = $app->getLocator();
        $renderer     = $locator->get('Zend\View\Renderer\PhpRenderer');
        $renderer->plugin('basePath')->setBasePath($basePath);
		
		
		/// json responder
		$locator             = $app->getLocator();
        $view                = $locator->get('Zend\View\View');
        $jsonStrategy = $locator->get('Zend\View\Strategy\JsonStrategy');
        $view->events()->attachAggregate($jsonStrategy, 1000);
		
		
    }
	
	public function initializeSessionControl($e) {
		$app          = $e->getParam('application');
        $locator      = $app->getLocator();
		
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
}
