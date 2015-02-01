<?php
namespace OliverHader\RelayrConnector\Handler;

use \OliverHader\RelayrConnector as Relayr;

class UpdateHandler extends Relayr\Handler\AbstractAppHandler {

	public function __construct(Relayr\Model\App $app) {
		$this->app = $app;
	}

	/**
	 * @param array $envelope
	 * @return bool
	 */
	public function update($envelope) {
		$device = $this->getDevice($envelope);

		if ($device === NULL) {
			return FALSE;
		}

		$device->getSensorData()->update(
			json_decode($envelope['message'], TRUE)
		);

		return TRUE;
	}

}