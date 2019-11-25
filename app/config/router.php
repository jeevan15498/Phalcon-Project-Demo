<?php

$router = $di->getRouter();

// Define your routes here

// https://docs.phalcon.io/3.4/en/routing#http-method-restrictions
// $router->add("/api/v1/get", "API::get", ["GET"]);
// $router->add("/api/v1/post", "API::post", ["POST"]);

$router->handle();
