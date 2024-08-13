<?php

require_once 'vendor/autoload.php';

if (strpos($_SERVER['HTTP_HOST'],'localhost') === 0) {
    $config_yaml = __DIR__.'/.config.local.yaml';
} else {
   $config_yaml = __DIR__.'/.htkadroconfig';
}

$updater = new \DarioSera\KadroApiCore\Updater($config_yaml);

$updater->runMigrations(__DIR__."/migrations/");
$updater->showResult();