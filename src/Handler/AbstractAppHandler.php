<?php
namespace OliverHader\RelayrConnector\Handler;

use \OliverHader\RelayrConnector as Relayr;

abstract class AbstractAppHandler implements HandlerInterface {

	/**
	 * @var Relayr\Model\App
	 */
	protected $app;

	/**
	 * @return Relayr\Model\App
	 */
	public function getApp() {
		return $this->app;
	}

	/**
	 * @param array $envelope
	 * @return NULL|NULL|Relayr\Model\Device
	 */
	protected function getDevice($envelope) {
		if (!isset($envelope['message']) || !isset($envelope['channel'])) {
			return NULL;
		}

		$device = $this->getApp()->getChannelDevice($envelope['channel']);

		return $device;
	}

}