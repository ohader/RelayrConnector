<?php
namespace OliverHader\RelayrConnector\Model\SensorData\Light;

use \OliverHader\RelayrConnector as Relayr;

class RgbColor {

	/**
	 * @var int
	 */
	protected $red;

	/**
	 * @var int
	 */
	protected $green;

	/**
	 * @var int
	 */
	protected $blue;

	/**
	 * @return int
	 */
	public function getRed() {
		return $this->red;
	}

	/**
	 * @return int
	 */
	public function getGreen() {
		return $this->green;
	}

	/**
	 * @return int
	 */
	public function getBlue() {
		return $this->blue;
	}

	/**
	 * @return string
	 */
	public function getHex() {
		$values = array($this->getRed(), $this->getGreen(), $this->getBlue());
		$values = array_map(array($this, 'padHex'), $values);
		return implode('', $values);
	}

	/**
	 * @param int $value
	 * @return string
	 */
	protected function padHex($value) {
		return str_pad(dechex($value), 2, '0', STR_PAD_LEFT);
	}

	/**
	 * @param array $data
	 */
	public function update(array $data) {
		$this->red = $data['r'];
		$this->green = $data['g'];
		$this->blue = $data['b'];
	}

}