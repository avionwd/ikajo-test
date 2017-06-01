<?php
/**
 * ${NAME} file
 *
 * @author Oleksandr Muzychenko <avionwd@gmail.com>
 */

/**
 * @var $config \Phalcon\Config
 */

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new \Phalcon\DI\FactoryDefault();
$di->set('config', $config);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function() use ($config) {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir($config->application->viewsDir);
    $view->registerEngines(array(
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php' // Generate Template files uses PHP itself as the template engine
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function() use ($config) {
    $config = $config->database->toArray();
    if (isset($config['adapter'])) {
        unset($config['adapter']);
    }

    return new \Phalcon\Db\Adapter\Pdo\Postgresql($config);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function() use ($config) {
    return new \Phalcon\Mvc\Model\Metadata\Memory();
});

/**
 * Dispatcher
 */
$di->set('dispatcher', function() {
    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setDefaultNamespace('app\controllers');
    $dispatcher->setDefaultController('message');

    $eventsManager = new \Phalcon\Events\Manager();
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function() {
    $session = new \Phalcon\Session\Adapter\Files();
    $session->start();

    return $session;
});

/**
 * Set shared assets manager
 */
$di->setShared('assets', 'Phalcon\Assets\Manager');

if ($config->offsetExists('mailer')) {
    $di->set('mailer', function () {
        $config = $this->get('config')->mailer;

        return new \Phalcon\Ext\Mailer\Manager($config->toArray());
    });
}