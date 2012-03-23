<?php
// ./config/autoload/database.config.php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'masterdb' => 'PDO',
            ),
            'masterdb' => array(
                'parameters' => array(
                    'dsn'            => 'sqlite:'.getcwd().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'theguild.sqlite',
                	'username' => '',
                	'passwd' => ''
                ),
            ),
        ),
    ),
);