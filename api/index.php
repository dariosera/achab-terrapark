<?php

require_once 'vendor/autoload.php';


if (strpos($_SERVER['HTTP_HOST'],'localhost') === 0) {
    $config_yaml = __DIR__.'/.config.local.yaml';
} else {
   $config_yaml = __DIR__.'/.config.yaml';
}

$app = new \DarioSera\KadroApiCore\App($config_yaml);
$app->setWebsiteMode();
$app->useModules(__DIR__ . "/modules/","frontend");
$app->uploadsFolder(__DIR__ . "/.uploads/");

$app->start();