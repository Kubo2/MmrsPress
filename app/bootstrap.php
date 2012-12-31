<?php

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
use Nette\Application\Routers\Route,
    Nette\Application\Routers\RouteList,
    Nette\Application\Routers\SimpleRouter;

// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';


// Configure application
$configurator = new Nette\Config\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->setProductionMode($configurator::AUTO);
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
        ->addDirectory(APP_DIR)
        ->addDirectory(LIBS_DIR)
        ->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();


// Setup router - hezké URL adresy - mod_rewrite

if (isset($_SERVER['NETTE_HTACCESS'])) { // FastCGI
    $container->router[] = new Route('index.php', 'Front:News:news', Route::ONE_WAY);

    $container->router[] = $adminRouter = new RouteList('Admin');
    $adminRouter[] = new Route('admin/<presenter>/<action>', 'Info:default'); // [/<id>]

    $container->router[] = $frontRouter = new RouteList('Front');
    $frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'News:news');
} else {
    $container->router = new SimpleRouter('Front:News:news');
}

// Configure and run the application!
//$container->application->errorPresenter = 'Error';
$container->application->run();

