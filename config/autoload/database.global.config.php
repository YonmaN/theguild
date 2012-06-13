<?php
// ./config/autoload/database.config.php

$dbParams = array(
    'database'  => '',
    'username'  => '',
    'password'  => '',
    'hostname'  => '',
);

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => function ($sm) use ($dbParams) {
                return new Zend\Db\Adapter\Adapter(array(
                    'driver'    => 'pdo',
                    'dsn'       => 'sqlite:'.getcwd().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'theguild.sqlite',
                    'database'  => $dbParams['database'],
                    'username'  => $dbParams['username'],
                    'password'  => $dbParams['password'],
                    'hostname'  => $dbParams['hostname'],
                ));
            },
        ),
    ),
//    'di' => array(
//        'instance' => array(
//            'alias' => array(
//                'masterdb' => 'PDO',
//            ),
//            'masterdb' => array(
//                'parameters' => array(
//                    'dsn'            => 'sqlite:'.getcwd().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'theguild.sqlite',
//                	'username' => '',
//                	'passwd' => ''
//                ),
//            ),
//        ),
//    ),
);
