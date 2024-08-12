<?php

require_once 'vendor/autoload.php';

if (strpos($_SERVER['HTTP_HOST'],'localhost') === 0) {
    $config_yaml = __DIR__.'/.config.local.yaml';
} else {
   $config_yaml = __DIR__.'/.htkadroconfig';
}

$app = new \DarioSera\KadroApiCore\App($config_yaml);
$app->useModules(__DIR__ . "/modules/");
$app->uploadsFolder(__DIR__ . "/.uploads/");

$app->start();