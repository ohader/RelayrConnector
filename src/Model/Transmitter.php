<?php
namespace OliverHader\RelayrConnector\Model;

use \OliverHader\RelayrConnector as Relayr;

class Transmitter {

	protected $app;
	protected $id;
	protected $name;
	protected $secret;

	/**
	 * @var \SplObjectStorage|Device[]
	 */
	protected $devices;

	public function __construct(App $app, $id, $name, $secret = NULL) {
		$this->app = $app;
		$this->id = $id;
		$this->name = $name;
		$this->secret = $secret;
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

	/**
	 * @return Device[]|\SplObjectStorage
	 */
	public function getDevices() {
		if (!isset($this->devices)) {
			$this->devices = Relayr\Service\RelayrService::getInstance()->getDevices($this);
		}
		return $this->devices;
	}

}