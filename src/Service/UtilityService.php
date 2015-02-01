<?php
namespace OliverHader\RelayrConnector\Service;

use \OliverHader\RelayrConnector as Relayr;

class UtilityService {

	const DATETIME_DefaultFormat = 'c';
	const DATETIME_PersistenceFormat = 'Y-m-d h:i:s';

	/**
	 * @var UtilityService
	 */
	static protected $instance;

	/**
	 * @return UtilityService
	 */
	static public function getInstance() {
		if (!isset(static::$instance)) {
			static::$instance = new UtilityService();
		}
		return static::$instance;
	}

	/**
	 * @param array $values
	 * @param \ArrayObject|Relayr\Model\Reading[] $readings
	 * @return array|Relayr\Model\Reading[]
	 */
	public function determineReadingAssignment(array $values, \ArrayObject $readings = NULL) {
		if ($readings === NULL) {
			return array();
		}

		$readings = $readings->getArrayCopy();
		$readingsAssignment = array();

		foreach ($values as $key => $value) {
			/** @var Relayr\Model\Reading $reading */
			foreach ($readings as $index => $reading) {
				if (strpos($reading->getMeaning(), $key) === 0) {
					$readingsAssignment[$key] = $reading;
					unset($values[$key]);
					unset($readings[$index]);
				}
			}
		}

		foreach ($values as $key => $value) {
			$readingsAssignment[$key] = array_shift($readings);
		}

		return $readingsAssignment;
	}

	/**
	 * @param int $timestamp
	 * @param string $format
	 * @return string
	 */
	public function formatMillisecondTimestamp($timestamp, $format = self::DATETIME_DefaultFormat) {
		$dateTime = new \DateTime();
		$dateTime->setTimestamp($timestamp / 1000);
		return $dateTime->format($format);
	}

}