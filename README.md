# Wunderbar Relayr Connector for PHP

*by [Oliver Hader](mailto:oliver@typo3.org)*

## Installation

*Note: This package is not available yet on packagist.org*

```
# git clone https://github.com/ohader/RelayrConnector.git
# cd RelayrConnector
# composer install
```

## How it works

+ connects to the Relayr REST API using your App data
+ authenticates against OAuth2 using simple cURL requests
+ retrieves token and user information from the Relayr API
+ retrieves transmitters and devices that are assigned
+ retrieves sensor data from PubNub by retrieved subscriptions

## Handlers

### UpdateHandler

Updates sensor data in the accordant models.

### PersistenceHandler

Persists sensor data from the accordant domain models using a PDO connection.

+ database table **sensor_data**
  + id: auto-incremented value
  + device: UUID of the sensor device
  + sensor_data_name: name of the the sensor data property (e.g. temperature, humidity, ...)
  + sensor_data_value: accordant sensor data value
  + sensor_data_timestamp: date-time of when the sensor data has been received

### RenderHandler

Renders a table of the current sensor data values.

## ConsoleView example

*find in the example/ directory*

```
TimeStamp: 1422724151.8372

LeetBar

+-------------+---------------------------+----------------------------------------------------------------------+
| Device      | TimeStamp                 | Sensor Data Values                                                   |
+-------------+---------------------------+----------------------------------------------------------------------+
| gyroscope   | 2015-01-31T18:09:11+01:00 | gyro: [x: -12.6, y: 17.12, z: 5.2], accel: [x: 0, y: -0.01, z: 1.01] |
| light       | 2015-01-31T18:09:11+01:00 | light: 263, clr: [r: 0, g: 0, b: 0], prox: 55                        |
| microphone  | 2015-01-31T18:09:10+01:00 | snd_level: 49                                                        |
| thermometer | 2015-01-31T18:09:10+01:00 | temp: 22.89, hum: 24.67                                              |
| infrared    | -/-                       | -/-                                                                  |
| bridge      | -/-                       | -/-                                                                  |
+-------------+---------------------------+----------------------------------------------------------------------+
```

## PdoPersistence example

This is similar to the previous ConsoleView example, except that data is persisted to a MySQL database connection.

## Next steps

* extend the generic sensor data model into specific sub-models