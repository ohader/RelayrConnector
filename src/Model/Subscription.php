<?php
namespace OliverHader\RelayrConnector\Model;

use \OliverHader\RelayrConnector as Relayr;

class Subscription {

	protected $app;
	protected $channel;
	protected $subscribeKey;
	protected $authKey;
	protected $cipherKey;

	public function __construct(App $app, $channel, $subscribeKey, $authKey = NULL, $cipherKey = NULL) {
		$this->app = $app;
		$this->channel = $channel;
		$this->subscribeKey = $subscribeKey;
		$this->authKey = $authKey;
		$this->cipherKey = $cipherKey;
	}

	public function getApp() {
		return $this->app;
	}

	public function getChannel() {
		return $this->channel;
	}

	public function getSubscribeKey() {
		return $this->subscribeKey;
	}

	public function getAuthKey() {
		return $this->authKey;
	}

	public function getCipherKey() {
		return $this->cipherKey;
	}

	/**
	 * @return string
	 */
	public function identify() {
		return sha1(
			implode('::', array($this->getSubscribeKey(), $this->getAuthKey(), $this->getCipherKey()))
		);
	}

}