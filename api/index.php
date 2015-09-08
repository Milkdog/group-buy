<?php
require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$blogPosts = array(
    1 => array(
        'date'      => '2011-03-29',
        'author'    => 'igorw',
        'title'     => 'Using Silex',
        'body'      => '...',
    ),
);

$app->get('/blog', function () use ($app, $blogPosts) {

    $output = $app->json($blogPosts);
    return $output;
});

$app->run();
