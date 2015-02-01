<?php
namespace OliverHader\RelayrConnector\Service;

use \OliverHader\RelayrConnector as Relayr;

class RelayrService {

	const API_URL = 'https://api.relayr.io/';

	/**
	 * @var RelayrService
	 */
	static protected $instance;

	/**
	 * @return RelayrService
	 */
	static public function getInstance() {
		if (!isset(static::$instance)) {
			static::$instance = new RelayrService();
		}
		return static::$instance;
	}

	/**
	 * @var \Guzzle\Http\Client
	 */
	protected $client;

	/**
	 * Singleton pattern, avoid direct creation.
	 */
	private function __construct() {
		$this->client = new \Guzzle\Http\Client();
		$this->client->setDefaultOption('allow_redirects', FALSE);
	}

	/**
	 * @param Relayr\Model\App $app
	 * @param string $username
	 * @param string $password
	 * @return Relayr\Model\Token
	 */
	public function authorize(Relayr\Model\App $app, $username, $password) {
		$parameters = array(
			'client_id' => $app->getId(),
			'app_name' => $app->getName(),
			'redirect_uri' => $app->getRedirectUri(),
			'response_type' => 'token',
			'scope' => 'access-own-user-info',
			'email' => $username,
			'password' => $password,
		);

		$request = $this->client->post(self::API_URL . 'oauth2/auth', NULL, $parameters);
		$response = $this->executeRequest($request);

		$location = $response->getLocation();

		if (empty($location) || strpos($location, '#') === FALSE) {
			throw new \RuntimeException('Expected HTTP Location Header was not valid nor present');
		}

		$tokenData = array();
		parse_str(substr(strstr($location, '#'), 1), $tokenData);

		return new Relayr\Model\Token(
			$app,
			$tokenData['access_token'],
			$tokenData['token_type'] . ' ' . $tokenData['access_token']
		);
	}

	/**
	 * @param Relayr\Model\App $app
	 * @return Relayr\Model\User
	 */
	public function getUser(Relayr\Model\App $app) {
		$request = $this->client->get(self::API_URL . 'oauth2/user-info');

		$userData = json_decode($this->executeRequest($request, $app->getToken())->getBody(), TRUE);
		if ($userData === FALSE) {
			throw new \RuntimeException('User could not be determined');
		}

		return new Relayr\Model\User(
			$app,
			$userData['id'],
			$userData['name'],
			$userData['email']
		);
	}

	/**
	 * @param Relayr\Model\User $user
	 * @return \SplObjectStorage|Relayr\Model\Transmitter[]
	 */
	public function getTransmitters(Relayr\Model\User $user) {
		$request = $this->client->get(self::API_URL . 'users/' . $user->getUserId() . '/transmitters');

		$transmittersData = json_decode($this->executeRequest($request, $user->getApp()->getToken())->getBody(), TRUE);
		if ($transmittersData === FALSE) {
			throw new \RuntimeException('Transmitters could not be determined');
		}

		$transmitters = new \SplObjectStorage();
		foreach ($transmittersData as $transmitterData) {
			$transmitter = new Relayr\Model\Transmitter(
				$user->getApp(),
				$transmitterData['id'],
				$transmitterData['name'],
				$transmitterData['secret']
			);
			$transmitters->attach($transmitter);
		}

		return $transmitters;
	}

	/**
	 * @param Relayr\Model\Transmitter $transmitter
	 * @return \SplObjectStorage|Relayr\Model\Device[]
	 */
	public function getDevices(Relayr\Model\Transmitter $transmitter) {
		$request = $this->client->get(self::API_URL . 'transmitters/' . $transmitter->getId() . '/devices');

		$devicesData = json_decode($this->executeRequest($request, $transmitter->getApp()->getToken())->getBody(), TRUE);
		if ($devicesData === FALSE) {
			throw new \RuntimeException('Transmitter Devices could not be determined');
		}

		$devices = new \SplObjectStorage();
		foreach ($devicesData as $deviceData) {
			$device = new Relayr\Model\Device(
				$transmitter->getApp(),
				$deviceData['id'],
				$deviceData['name'],
				$deviceData['secret'],
				$deviceData['description'],
				$deviceData['public']
			);
			$device->setFirmwareVersion($deviceData['firmwareVersion']);
			$device->setModelId($deviceData['model']);
			$devices->attach($device);
		}
		return $devices;
	}

	/**
	 * @return \ArrayObject|Relayr\Model\Model[]
	 */
	public function getModels() {
		$request = $this->client->get(self::API_URL . 'device-models');

		$modelsData = json_decode($this->executeRequest($request)->getBody(), TRUE);
		if ($modelsData === FALSE) {
			throw new \RuntimeException('Models could not be determined');
		}

		$models = new \ArrayObject();
		foreach ($modelsData as $modelData) {
			$model = Relayr\Model\Model::create($modelData);
			$models[$model->getId()] = $model;
		}
		return $models;
	}

	public function getSubscription(Relayr\Model\Device $device) {
		$app = $device->getApp();
		$request = $this->client->post(self::API_URL . 'apps/' . $app->getId() . '/devices/' . $device->getId());

		$subscriptionData = json_decode($this->executeRequest($request, $app->getToken())->getBody(), TRUE);
		if ($subscriptionData === FALSE) {
			throw new \RuntimeException('Subscription could not be determined');
		}

		return new Relayr\Model\Subscription(
			$app,
			$subscriptionData['channel'],
			$subscriptionData['subscribeKey'],
			$subscriptionData['authKey'],
			$subscriptionData['cipherKey']
		);
	}

	/**
	 * @param \Guzzle\Http\Message\RequestInterface $request
	 * @param NULL|Relayr\Model\Token $token
	 * @return \Guzzle\Http\Message\Response
	 */
	protected function executeRequest(\Guzzle\Http\Message\RequestInterface $request, Relayr\Model\Token $token = NULL) {
		if ($token !== NULL) {
			$request->setHeader('Authorization', $token->getBearer());
		}

		try {
			$response = $request->send();
			return $response;
		} catch (\Guzzle\Http\Exception\BadResponseException $exception) {
			throw new \RuntimeException($exception->getResponse()->getMessage());
		}
	}

}