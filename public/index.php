<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));
set_include_path(get_include_path() . PATH_SEPARATOR . getcwd() . DIRECTORY_SEPARATOR . 'vendor/ZendFramework/library');
// Setup autoloading
include 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(include 'config/application.config.php')->run();
