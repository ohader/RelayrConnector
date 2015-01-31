<?php
namespace OliverHader\RelayrConnector\Model;

use \OliverHader\RelayrConnector as Relayr;

class Device {

	protected $app;
	protected $id;
	protected $name;
	protected $secret;
	protected $description;
	protected $public;

	protected $modelId;
	protected $firmwareVersion;

	protected $subscription;
	protected $sensorData;

	public function __construct(App $app, $id, $name, $secret = NULL, $description = NULL, $public = FALSE) {
		$this->app = $app;
		$this->id = $id;
		$this->name = $name;
		$this->secret = $secret;
		$this->description = $description;
		$this->public = (bool)$public;

		$this->sensorData = new Relayr\Model\SensorData\Generic();
	}

	public function getApp() {
		return $this->app;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getSecret() {
		return $this->secret;
	}

	public function getDescription() {
		return $this->description;
	}

	public function isPublic() {
		return (bool)$this->public;
	}

	public function getFirmwareVersion() {
		return $this->firmwareVersion;
	}

	public function setFirmwareVersion($firmwareVersion) {
		$this->firmwareVersion = $firmwareVersion;
	}

	public function getModelId() {
		return $this->modelId;
	}

	public function setModelId($modelId) {
		$this->modelId = $modelId;
	}

	public function getSubscription() {
		if (!isset($this->subscription)) {
			$this->subscription = Relayr\Service\RelayrService::getInstance()->getSubscription($this);
		}
		return $this->subscription;
	}

	public function getSensorData() {
		return $this->sensorData;
	}

}