<?php
return array(
	'router' => array(
        'routes' => array(
            'default' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/[:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'index',
                        'action'     => 'index',
                    ),
                ),
            ),
			'myself' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/myself',
					'defaults' => array(
						'controller' => 'guilduser',
						'action'     => 'index',
					),
				),
			),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controller' => array(
        'classes' => array(
            'index' => 'Application\Controller\IndexController',
            'guilduser' => 'GuildUser\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'index/index'   => __DIR__ . '/../view/index/index.phtml',
            'error/404'     => __DIR__ . '/../view/error/404.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
			'zfc-user/user/login' => __DIR__ . '/../view/zfcuser/login.phtml',
			'zfc-user/user/register' => __DIR__ . '/../view/zfcuser/register.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
		
		'helper_map' => array(
			'slider' => 'MooTools\View\Helper\Slider'
		),
		'strategies' => array(
			'Zend\View\Strategy\JsonStrategy'
		)
    ),

	
//	
//	
    'di' => array(
        'instance' => array(
        	'alias' => array(
        				'game_tg' => 'Zend\Db\TableGateway\TableGateway',
        				'user_game_tg' => 'Zend\Db\TableGateway\TableGateway',
        				'profile_tg' => 'Zend\Db\TableGateway\TableGateway',
        				'user_tg' => 'Zend\Db\TableGateway\TableGateway',
        			),
//        	
        		'user_game_tg' => array(
        				'parameters' => array(
        						'table' => 'user_game',
        						'adapter'   => 'zfcuser_zend_db_adapter',
        				),
        		),
        	
        		'user_tg' => array(
        				'parameters' => array(
        						'table' => 'user',
        						'adapter'   => 'zfcuser_zend_db_adapter',
        				),
        		),
        		'game_tg' => array(
        				'parameters' => array(
        						'table' => 'game',
        						'adapter'   => 'zfcuser_zend_db_adapter',
        				),
        		),
        		'profile_tg' => array(
        				'parameters' => array(
        						'table' => 'profile',
        						'adapter'   => 'zfcuser_zend_db_adapter',
        				),
        		),
        	'GuildUser\Model\UserMapper' => array(
        		'parameters' => array('tableGateway' => 'user_tg')
        	),
        	'GuildUser\Model\GameMapper' => array(
        		'parameters' => array('tableGateway' => 'user_game_tg')
        	),
        	'Game\Model\GameMapper' => array(
        		'parameters' => array('tableGateway' => 'game_tg')
        	),
        	'GuildUser\Model\ProfileMapper' => array(
        		'parameters' => array('tableGateway' => 'profile_tg',
										'userMapper' => 'ZfcUser\Model\UserMapper',
					),
				
        	),
        	'Application\Controller\IndexController' => array(
        				'parameters' => array(
        					'userMapper' => 'GuildUser\Model\UserMapper',
        					'profileMapper' => 'GuildUser\Model\ProfileMapper',
        					'gameMapper' => 'Game\Model\GameMapper',
        					'userGameMapper' => 'GuildUser\Model\GameMapper',
        				)
        			),
        	'GuildUser\Controller\IndexController' => array(
        				'parameters' => array(
        					'profileMapper' => 'GuildUser\Model\ProfileMapper',
        					'userMapper' => 'GuildUser\Model\UserMapper',
        					'gameMapper' => 'Game\Model\GameMapper',
        				)
        			),
//            // Setup for controllers.
//
//            // Injecting the plugin broker for controller plugins into
//            // the action controller for use by all controllers that
//            // extend it
//            'Zend\Mvc\Controller\ActionController' => array(
//                'parameters' => array(
//                    'broker'       => 'Zend\Mvc\Controller\PluginBroker',
//                ),
//            ),
//            'Zend\Mvc\Controller\PluginBroker' => array(
//                'parameters' => array(
//                    'loader' => 'Zend\Mvc\Controller\PluginLoader',
//                ),
//            ),
//			'Zend\View\HelperLoader' => array(
//				'parameters' => array(
//					'map' => array(

//					)
//				)
//			),
//            // Setup for router and routes
//            'Zend\Mvc\Router\RouteStack' => array(
//                'parameters' => array(
//                    'routes' => array(
//                        'default' => array(
//                            'type'    => 'Zend\Mvc\Router\Http\Segment',
//                            'options' => array(
//                                'route'    => '/[:controller[/:action]]',
//                                'constraints' => array(
//                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                ),
//                                'defaults' => array(
//                                    'controller' => 'Application\Controller\IndexController',
//                                    'action'     => 'index',
//                                ),
//                            ),
//                        ),
//                        'myself' => array(
//                            'type' => 'Zend\Mvc\Router\Http\Literal',
//                            'options' => array(
//                                'route'    => '/myself',
//                                'defaults' => array(
//                                    'controller' => 'guilduser',
//                                    'action'     => 'index',
//                                ),
//                            ),
//                        ),
//                        'home' => array(
//                            'type' => 'Zend\Mvc\Router\Http\Literal',
//                            'options' => array(
//                                'route'    => '/',
//                                'defaults' => array(
//                                    'controller' => 'Application\Controller\IndexController',
//                                    'action'     => 'index',
//                                ),
//                            ),
//                        ),
//                    ),
//                ),
//            ),
//
//            // Setup for the view layer.
//
//            // Using the PhpRenderer, which just handles html produced by php 
//            // scripts
//            'Zend\View\Renderer\PhpRenderer' => array(
//                'parameters' => array(
//                    'resolver' => 'Zend\View\Resolver\AggregateResolver',
//                ),
//            ),
//            // Defining how the view scripts should be resolved by stacking up
//            // a Zend\View\Resolver\TemplateMapResolver and a
//            // Zend\View\Resolver\TemplatePathStack
//            'Zend\View\Resolver\AggregateResolver' => array(
//                'injections' => array(
//                    'Zend\View\Resolver\TemplateMapResolver',
//                    'Zend\View\Resolver\TemplatePathStack',
//                ),
//            ),
//            // Defining where the layout/layout view should be located
//            'Zend\View\Resolver\TemplateMapResolver' => array(
//                'parameters' => array(
//                    'map'  => array(
//                        'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
//                    	// override the default view script for zfcuser/login and register
//                    	'zfcuser/login' => __DIR__ . '/../view/zfcuser/login.phtml',
//                    	'zfcuser/register' => __DIR__ . '/../view/zfcuser/register.phtml',
//                    ),
//                ),
//            ),
//            // Defining where to look for views. This works with multiple paths,
//            // very similar to include_path
//            'Zend\View\Resolver\TemplatePathStack' => array(
//                'parameters' => array(
//                    'paths'  => array(
//                        'application' => __DIR__ . '/../view',
//                    ),
//                ),
//            ),
//            // View for the layout
//            'Zend\Mvc\View\DefaultRenderingStrategy' => array(
//                'parameters' => array(
//                    'layoutTemplate' => 'layout/layout',
//                ),
//            ),
//            // Injecting the router into the url helper
//            'Zend\View\Helper\Url' => array(
//                'parameters' => array(
//                    'router' => 'Zend\Mvc\Router\RouteStack',
//                ),
//            ),
//            // Configuration for the doctype helper
//            'Zend\View\Helper\Doctype' => array(
//                'parameters' => array(
//                    'doctype' => 'HTML5',
//                ),
//            ),
//            // View script rendered in case of 404 exception
//            'Zend\Mvc\View\RouteNotFoundStrategy' => array(
//                'parameters' => array(
//                    'displayNotFoundReason' => true,
//                    'displayExceptions'     => true,
//                    'notFoundTemplate'      => 'error/404',
//                ),
//            ),
//            // View script rendered in case of other exceptions
//            'Zend\Mvc\View\ExceptionStrategy' => array(
//                'parameters' => array(
//                    'displayExceptions' => true,
//                    'exceptionTemplate' => 'error/index',
//                ),
//            ),
//			'Zend\View\Strategy\JsonStrategy' => array(
//				'parameters' => array(
//					'renderer' => 'Zend\View\Renderer\JsonRenderer'
//					
//				),
//			)
        ),
    ),
);
