<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */

//  https://docs.phalcon.io/3.4/en/namespaces
$loader->registerNamespaces(
    [
       "security" => $config->application->modelsDir
    ]
);

$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->pluginsDir,
    ]
)->register();
