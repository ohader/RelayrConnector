<?php
namespace OliverHader\RelayrConnector\Model;

use \OliverHader\RelayrConnector as Relayr;

class Model {

	const MODEL_WunderbarAccelerometerGyroscope = '173c44b5-334e-493f-8eb8-82c8cc65d29f';
	const MODEL_WunderbarLightProximitySensor = 'a7ec1b21-8582-4304-b1cf-15a1fc66d1e8';
	const MODEL_WunderbarMicrophone = '4f38b6c6-a8e9-4f93-91cd-2ac4064b7b5a';
	const MODEL_WunderbarThermometerHumiditySensor = 'ecf6cf94-cb07-43ac-a85e-dccf26b48c86';
	const MODEL_WunderbarInfraredSensor = 'bab45b9c-1c44-4e71-8e98-a321c658df47';
	const MODEL_WunderbarBridgeModule = 'ebd828dd-250c-4baf-807d-69d85bed065b';

	protected $id;
	protected $name;
	protected $manufacturer;
	protected $readings;

	static public function create(array $data) {
		$model = new Model($data['id'], $data['name'], $data['manufacturer']);

		if (!empty($data['readings'])) {
			foreach ($data['readings'] as $readingData) {
				$reading = Reading::create($readingData);
				$model->addReading($reading);
			}
		}

		return $model;
	}

	public function __construct($id, $name, $manufacturer = NULL) {
		$this->id = $id;
		$this->name = $name;
		$this->manufacturer = $manufacturer;

		$this->readings = new \ArrayObject();
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getManufacturer() {
		return $this->manufacturer;
	}

	public function addReading(Reading $reading) {
		$this->readings->append($reading);
	}

	public function getReadings() {
		return $this->readings;
	}

}