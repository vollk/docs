<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Vollk
 * Date: 17/05/15
 * Time: 16:27
 */
require_once '../vendor/autoload.php';
require_once '../app/Application.php';

$app = new Application();

$app['debug'] = true;

$app->before(function() use($app) {
    $app->initDatabase();
});

$app->get('/hello/{name}', function($name) use($app) {
    return $app->json(array('id'=>1,'name'=>'sdf'));
});

$app->get('/db', function() use ($app) {
    $conn = $app['db'];
    $res = $conn->query("select * from acts limit 1")->fetch();
    return $app->json($res,200,array('Content-Type'=>'application/json; charset=utf-8'));
});

$app->get('/acts/print/{id}', function($id) use ($app) {
    $model= $app->createModel('Acts');
    $model->printAct($app, (int)$id);
    return 1;
});
$app->run();