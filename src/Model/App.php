<?php
namespace OliverHader\RelayrConnector\Model;

use \OliverHader\RelayrConnector as Relayr;

class App {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $redirectUri;

	/**
	 * @var Token
	 */
	protected $token;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var \SplObjectStorage|Transmitter[]
	 */
	protected $transmitters;

	/**
	 * @var array
	 */
	protected $channelDevices;

	public function __construct($id, $name = NULL, $redirectUri = 'http://localhost') {
		$this->id = $id;
		$this->name = $name;
		$this->redirectUri = $redirectUri;

		$this->transmitters;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getRedirectUri() {
		return $this->redirectUri;
	}

	public function getToken() {
		return $this->token;
	}

	public function authorize($username, $password) {
		$this->token = Relayr\Service\RelayrService::getInstance()->authorize($this, $username, $password);
	}

	public function getUser() {
		if (!isset($this->user)) {
			$this->user = Relayr\Service\RelayrService::getInstance()->getUser($this);
		}
		return $this->user;
	}

	public function getTransmitters() {
		if (!isset($this->transmitters)) {
			$this->transmitters = Relayr\Service\RelayrService::getInstance()->getTransmitters($this->getUser());
		}
		return $this->transmitters;
	}

	/**
	 * @param string $channel
	 * @return NULL|Device
	 */
	public function getChannelDevice($channel) {
		$this->collectChannelDevices();

		if (isset($this->channelDevices[$channel])) {
			return $this->channelDevices[$channel];
		}

		return NULL;
	}

	protected function collectChannelDevices() {
		if (isset($this->channelDevices)) {
			return;
		}

		foreach ($this->transmitters as $transmitter) {
			foreach ($transmitter->getDevices() as $device) {
				$channel = $device->getSubscription()->getChannel();
				if (isset($this->channelDevices[$channel])) {
					throw new \RuntimeException('Duplicate assignment of channel "' . $channel . '"');
				}
				$this->channelDevices[$channel] = $device;
			}
		}
	}

}