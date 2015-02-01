<?php
namespace OliverHader\RelayrConnector\Model\SensorData;

use \OliverHader\RelayrConnector as Relayr;

class Generic {

	/**
	 * @param Relayr\Model\Model $model
	 * @return Generic|Light|Temperature
	 */
	static public function create(Relayr\Model\Model $model) {
		if ($model->is(Relayr\Model\Model::MODEL_WunderbarThermometerHumiditySensor)) {
			return new Temperature();
		}
		if ($model->is(Relayr\Model\Model::MODEL_WunderbarLightProximitySensor)) {
			return new Light();
		}
		return new Generic();
	}

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