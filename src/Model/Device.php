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

	protected $model;
	protected $subscription;
	protected $sensorData;

	/**
	 * @param App $app
	 * @param array $data
	 * @return Device
	 */
	static public function create(App $app, array $data) {
		$device = new Relayr\Model\Device(
			$app,
			$data['id'],
			$data['name'],
			$data['model'],
			$data['secret'],
			$data['description'],
			$data['public']
		);
		if (isset($data['firmwareVersion'])) {
			$device->setFirmwareVersion($data['firmwareVersion']);
		}
		return $device;
	}

	public function __construct(App $app, $id, $name, $modelId, $secret = NULL, $description = NULL, $public = FALSE) {
		$this->app = $app;
		$this->id = $id;
		$this->name = $name;
		$this->modelId = $modelId;
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

	public function getModel() {
		if (!isset($this->model)) {
			$this->model = Relayr\Repository\ModelRepository::getInstance()->findById($this->getModelId());
		}
		return $this->model;
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