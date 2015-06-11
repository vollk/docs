<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Vollk
 * Date: 17/05/15
 * Time: 16:27
 */
$app_dir = '../../docs_app';
require_once $app_dir.'/vendor/autoload.php';
require_once $app_dir.'/app/Application.php';

$app = Application::getInstance();

$app['debug'] = true;

$app->get('', function() {
    ob_start();
        include 'layout.html';
    $res = ob_get_contents();
    ob_end_clean();
    return $res;
});

$app->get('/acts/print/{id}', function($id) use ($app) {
    $model= $app->createModel('Acts');
    $file = $model->printAct((int)$id);
    $file_name = basename($file);
    $resp = $app->sendFile($file,200, array('Content-Disposition' => 'attachment; filename="'.$file_name.'"'));
    $resp->deleteFileAfterSend(true);
    return $resp;
});

$app->get('/bills/print/{id}', function($id) use ($app) {
    $model= $app->createModel('Bills');
    $file = $model->printBill((int)$id);
    $file_name = basename($file);
    $resp = $app->sendFile($file,200, array('Content-Disposition' => 'attachment; filename="'.$file_name.'"'));
    $resp->deleteFileAfterSend(true);
    return $resp;
});

$app->run();