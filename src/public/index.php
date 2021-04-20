<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config = include('../config/config.php');

/*
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$config['api']['base'] = 'http://localhost:1337/';
*/

/* APP INIT */
$app = new \Slim\App(['settings' => $config]);

/* DIC */
$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer('../templates/');
$container['api'] = function ($c) {
    $api = $c['settings']['api'];
    return $api;
};
/* ROUTES */
$app->get('/{slug}', function (Request $request, Response $response, array $args) {
    $slug = $args['slug'];

    /* fetch the profile data */
    $fetch = json_decode(file_get_contents($this->api['base'] . 'profiles/?slug=' . $slug));
    $profile = $fetch[0];
    /* set the template */
    $template = 'default.phtml';
    if($profile->template){$template = $profile->template . '.phtml';}
    /* render */
    $response = $this->view->render($response, $template, ['profile' => $profile]);

    return $response;
});
$app->run();