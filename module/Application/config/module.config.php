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
			'slider' => 'MooTools\View\Helper\Slider',
			'gameIcon' => 'Game\View\Helper\GameIcon'
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
        					'gameMapper' => 'GuildUser\Model\GameMapper',
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
        ),
    ),
);
