<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

try {
    /**
     * Read the configuration
     * @var $config Phalcon\Config
     */
    $config = include __DIR__ . "/../configs/config.php";

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../configs/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../configs/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();
} catch (Phalcon\Exception $e) {
    echo $e->getMessage();
} catch (PDOException $e){
    echo $e->getMessage();
}