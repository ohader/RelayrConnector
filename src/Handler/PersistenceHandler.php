<?php
namespace OliverHader\RelayrConnector\Handler;

use \OliverHader\RelayrConnector as Relayr;

class PersistenceHandler extends AbstractAppHandler {

	const TABLE_SensorData = 'sensor_data';

	static public function create(Relayr\Model\App $app, $dsn, $username = NULL, $password = NULL) {
		$connection = new \PDO($dsn, $username, $password);
		return new PersistenceHandler($app, $connection);
	}

	protected $connection;

	/**
	 * @param Relayr\Model\App $app
	 * @param \PDO $connection
	 */
	public function __construct(Relayr\Model\App $app, \PDO $connection) {
		$this->app = $app;
		$this->connection = $connection;
		$this->ensureSchema();
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

		$bindDevice = $device->getId();
		$bindSensorDataName = '';
		$bindSensorDataValue = '';
		$bindSensorDataTimestamp = Relayr\Service\UtilityService::getInstance()->formatMillisecondTimestamp(
			$device->getSensorData()->getTimeStamp(),
			Relayr\Service\UtilityService::DATETIME_PersistenceFormat
		);

		$bindings = array(
			'device' => ':device',
			'sensor_data_name' => ':sensor_data_name',
			'sensor_data_value' => ':sensor_data_value',
			'sensor_data_timestamp' => ':sensor_data_timestamp',
		);

		$statement = $this->connection->prepare(
			'INSERT INTO ' . static::TABLE_SensorData
			. ' (' . implode(', ', array_keys($bindings)) . ') VALUES ('
			. implode(', ', array_values($bindings)) . ')'
		);

		// Bindings are per reference here
		$statement->bindParam(':device', $bindDevice);
		$statement->bindParam(':sensor_data_name', $bindSensorDataName);
		$statement->bindParam(':sensor_data_value', $bindSensorDataValue);
		$statement->bindParam(':sensor_data_timestamp', $bindSensorDataTimestamp);

		foreach ($device->getSensorData()->__toFlatArray() as $sensorDataName => $sensorDataValue) {
			$bindSensorDataName = $sensorDataName;
			$bindSensorDataValue = $sensorDataValue;
			$result = $statement->execute();

			if ($result === FALSE) {
				throw new \RuntimeException('Persisting failed with "' . join(': ', $statement->errorInfo()) . '"');
			}
		}

		return TRUE;
	}

	public function flushSensorData() {
		$query = 'DELETE FROM ' . static::TABLE_SensorData . ';';
		$result = $this->connection->exec($query);

		if ($result === FALSE) {
			throw new \RuntimeException('Flushing sensor data returned "' . join(': ', $this->connection->errorInfo()) . '"');
		}
	}

	protected function ensureSchema() {
		$columns = array(
			'id INT(11) AUTO_INCREMENT PRIMARY KEY',
			'device VARCHAR(36) NOT NULL',
			'sensor_data_name VARCHAR(128) NOT NULL',
			'sensor_data_value VARCHAR(32) NOT NULL',
			'sensor_data_timestamp DATETIME NOT NULL',
		);

		$query = 'CREATE TABLE IF NOT EXISTS ' . static::TABLE_SensorData . ' (' . implode(', ', $columns) . ');';
		$result = $this->connection->exec($query);

		if ($result === FALSE) {
			throw new \RuntimeException('Schema creating failed with "' . join(': ', $this->connection->errorInfo()) . '"');
		}
	}

}