<?php
namespace OliverHader\RelayrConnector\Model\SensorData;

use \OliverHader\RelayrConnector as Relayr;

class Temperature extends Generic {

	/**
	 * @var float
	 */
	protected $temperature;

	/**
	 * @var float
	 */
	protected $humidity;

	/**
	 * @return float
	 */
	public function getTemperature() {
		return $this->temperature;
	}

	/**
	 * @return float
	 */
	public function getHumidity() {
		return $this->humidity;
	}

	/**
	 * @param array $data
	 */
	public function update(array $data) {
		parent::update($data);

		if (isset($data['temp'])) {
			$this->temperature = $data['temp'];
		}
		if (isset($data['hum'])) {
			$this->humidity = $data['hum'];
		}
	}

	/**
	 * @return array
	 */
	public function __toFlatArray() {
		$flatArray = array(
			'temperature' => $this->getTemperature(),
			'humidity' => $this->getHumidity(),
		);
		return $flatArray;
	}

}