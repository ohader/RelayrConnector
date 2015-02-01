<?php
require 'vendor/autoload.php';

use \OliverHader\RelayrConnector as Relayr;

$appId = '3da425c3-71e0-4763-acef-96785a5c5b8b';
$appName = 'Collector';
$redirectUri = 'http://localhost';

$username = '<your-relayr-username-here>';
$password = '<your-relayr-password-here>';

$app = new Relayr\Model\App($appId, $appName);
$app->authorize($username, $password);

// Define interest in sensor models
// If all sensors shall be used, just set it to NULL
// (or don't use the $interest argument further down)
$interest = array(
	Relayr\Model\Model::MODEL_WunderbarThermometerHumiditySensor,
	Relayr\Model\Model::MODEL_WunderbarLightProximitySensor,
);

$renderer = new Relayr\Service\Cli\RenderService($app, $interest);
Relayr\Service\PubNubService::getInstance()->subscribe($app, array($renderer, 'update'), $interest);