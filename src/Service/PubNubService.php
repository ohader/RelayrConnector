<?php
namespace OliverHader\RelayrConnector\Service;

use \OliverHader\RelayrConnector as Relayr;

class PubNubService {

	/**
	 * @var PubNubService
	 */
	static protected $instance;

	/**
	 * @return PubNubService
	 */
	static public function getInstance() {
		if (!isset(static::$instance)) {
			static::$instance = new PubNubService();
		}
		return static::$instance;
	}

	/**
	 * @var array|\Pubnub\Pubnub[]
	 */
	protected $connections;

	public function subscribe(Relayr\Model\App $app, $callback) {
		foreach ($this->determineAssignments($app) as $assignment) {
			$assignment->getConnection()->subscribe(
				$assignment->getChannels()->getArrayCopy(),
				$callback
			);
		}
	}

	/**
	 * @param Relayr\Model\App $app
	 * @return Relayr\Model\Assignment[]
	 */
	protected function determineAssignments(Relayr\Model\App $app) {
		/** @var Relayr\Model\Assignment[] $assignments */
		$assignments = array();

		foreach ($app->getTransmitters() as $transmitter) {
			foreach ($transmitter->getDevices() as $device) {
				$subscription = $device->getSubscription();
				$identifier = $subscription->identify();

				if (!isset($assignments[$identifier])) {
					$assignments[$identifier] = new Relayr\Model\Assignment(
						$this->getConnection($subscription)
					);
				}

				$assignments[$identifier]->addChannel($subscription->getChannel());
			}
		}

		return $assignments;
	}

	/**
	 * @param Relayr\Model\Subscription $subscription
	 * @return \Pubnub\Pubnub
	 */
	protected function getConnection(Relayr\Model\Subscription $subscription) {
		$identifier = $subscription->identify();
		if (!isset($this->connections[$identifier])) {
			$this->connections[$identifier] = new \Pubnub\Pubnub(
					array(
						'origin' => 'pubsub.pubnub.com',
						'publish_key' => $subscription->getSubscribeKey(),
						'subscribe_key' => $subscription->getSubscribeKey(),
						'auth_key' => $subscription->getAuthKey(),
						'cipher_key' => $subscription->getCipherKey(),
						'pem_path' => dirname(__DIR__),
						'ssl' => TRUE,
					)
			);
		}
		return $this->connections[$identifier];
	}

}