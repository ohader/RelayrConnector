<?php
namespace OliverHader\RelayrConnector\Model;

use \OliverHader\RelayrConnector as Relayr;

class Reading {

	protected $meaning;
	protected $unit;
	protected $maximum;
	protected $minimum;
	protected $precision;

	static public function create(array $data) {
		$reading = new Reading($data['meaning'], $data['unit']);

		if (isset($data['maximum'])) {
			$reading->setMaximum($data['maximum']);
		}
		if (isset($data['minimum'])) {
			$reading->setMinimum($data['minimum']);
		}
		if (isset($data['precision'])) {
			$reading->setPrecision($data['precision']);
		}

		return $reading;
	}

	public function __construct($meaning, $unit) {
		$this->meaning = $meaning;
		$this->unit = $unit;
	}

	public function getMeaning() {
		return $this->meaning;
	}

	public function getUnit() {
		return $this->unit;
	}

	public function getMaximum() {
		return $this->maximum;
	}

	public function setMaximum($maximum) {
		$this->maximum = $maximum;
	}

	public function getMinimum() {
		return $this->minimum;
	}

	public function setMinimum($minimum) {
		$this->minimum = $minimum;
	}

	public function getPrecision() {
		return $this->precision;
	}

	public function setPrecision($precision) {
		$this->precision = $precision;
	}

}