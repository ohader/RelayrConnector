# Wunderbar Relayr Connector

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
