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

// Handler taking care of updating sensor data results
$updateHandler = new Relayr\Handler\UpdateHandler($app);
// Handler taking care of persisting data to a PDO connection
$persistenceHandler = Relayr\Handler\PersistenceHandler::create(
	$app,
	'mysql:dbname=connector;host=127.0.0.1',
	'<your-database-username>',
	'<your-database-password>'
);
// Handler taking care of rendering in general
$renderHandler = new Relayr\Handler\Cli\RenderHandler($app, $interest);
// Handler combining previous handlers and defining execution order
$handler = new Relayr\Handler\ChainedHandler();
$handler->register($updateHandler)->register($persistenceHandler)->register($renderHandler);

// Subscribe to PubNub service and invoke handlers if updates are received (= pulled in this case)
Relayr\Service\PubNubService::getInstance()->subscribe($app, array($handler, 'update'), $interest);