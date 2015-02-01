<?php
namespace OliverHader\RelayrConnector\Model\SensorData;

use \OliverHader\RelayrConnector as Relayr;

class Light extends Generic {

	/**
	 * @var int
	 */
	protected $light;

	/**
	 * @var Relayr\Model\SensorData\Light\RgbColor
	 */
	protected $color;

	/**
	 * @var int
	 */
	protected $proximity;

	public function __construct() {
		$this->color = new Relayr\Model\SensorData\Light\RgbColor();
	}

	/**
	 * @return int
	 */
	public function getLight() {
		return $this->light;
	}

	/**
	 * @return Relayr\Model\SensorData\Light\RgbColor
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @return int
	 */
	public function getProximity() {
		return $this->proximity;
	}

	/**
	 * @param array $data
	 */
	public function update(array $data) {
		parent::update($data);

		if (isset($data['light'])) {
			$this->light = $data['light'];
		}
		if (isset($data['color'])) {
			$this->color->update($data['color']);
		}
		if (isset($data['prox'])) {
			$this->proximity = $data['prox'];
		}
	}

}