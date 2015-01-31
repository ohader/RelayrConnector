<?php
namespace OliverHader\RelayrConnector\Model\SensorData;

use \OliverHader\RelayrConnector as Relayr;

class Generic {

	protected $values = array();
	protected $timeStamp;

	public function getValues() {
		return $this->values;
	}

	public function getTimeStamp() {
		return $this->timeStamp;
	}

	public function update(array $data) {
		if (isset($data['ts'])) {
			$this->timeStamp = $data['ts'];
			unset($data['ts']);
		}
		$this->values = $data;
	}

}