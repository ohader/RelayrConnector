<?php
namespace OliverHader\RelayrConnector\Service\Cli;

use \OliverHader\RelayrConnector as Relayr;

class RenderService {

	/**
	 * @var Relayr\Model\App
	 */
	protected $app;
	protected $models;

	protected $lastTimeStamp;
	protected $renderInterval = 1.0;

	public function __construct(Relayr\Model\App $app, array $models = NULL) {
		$this->app = $app;
		$this->models = $models;
	}

	public function getApp() {
		return $this->app;
	}

	public function update($envelope) {
		if (!isset($envelope['message']) || !isset($envelope['channel'])) {
			return FALSE;
		}

		$device = $this->getApp()->getChannelDevice($envelope['channel']);
		if ($device === NULL) {
			return FALSE;
		}

		$device->getSensorData()->update(
			json_decode($envelope['message'], TRUE)
		);

		$this->render();

		return TRUE;
	}

	public function render() {
		$currentTimeStamp = microtime(TRUE);
		if (isset($this->lastTimeStamp) && $this->lastTimeStamp + $this->renderInterval > $currentTimeStamp) {
			return;
		}

		$this->lastTimeStamp = $currentTimeStamp;

		system('clear');

		$output = new \Symfony\Component\Console\Output\ConsoleOutput();
		$output->writeln('TimeStamp: ' . $this->lastTimeStamp);
		$output->writeln('');

		foreach ($this->getApp()->getTransmitters() as $transmitter) {
			$output->writeln($transmitter->getName());
			$output->writeln('');

			$table = new \Symfony\Component\Console\Helper\Table($output);
			$table->setHeaders(array('Device', 'TimeStamp', 'Sensor Data Values'));

			$rowIndex = 0;
			foreach ($transmitter->getDevices() as $device) {
				if ($this->models !== NULL && !in_array($device->getModelId(), $this->models)) {
					continue;
				}

				if ($rowIndex++ > 0) {
					$table->addRow(new \Symfony\Component\Console\Helper\TableSeparator());
				}

				$table->addRow(array(
					$device->getName(),
					$this->datify(
						$device->getSensorData()->getTimeStamp() / 1000
					),
					$this->combine(
						$device->getSensorData()->getValues()
					)
				));
			}

			$table->render();
		}
	}

	/**
	 * @param array $array
	 * @return string
	 */
	protected function combine(array $array) {
		$elements = array();

		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$elements[] = $key . ': [' . $this->combine($value) . ']';
			} else {
				$elements[] = $key . ': ' . $value;
			}
		}

		return $this->emptify(implode(', ', $elements));
	}

	protected function datify($value) {
		$result = '';

		if (!empty($value)) {
			$dateTime = new \DateTime();
			$dateTime->setTimestamp($value);
			$result = $dateTime->format('c');
		}

		return $this->emptify($result);
	}

	protected function emptify($value, $emptyValue = '-/-') {
		if (empty($value)) {
			return $emptyValue;
		}
		return $value;
	}

}