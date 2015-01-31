<?php
namespace OliverHader\RelayrConnector\Model;

use \OliverHader\RelayrConnector as Relayr;

class Assignment {

	protected $connection;
	protected $channels;

	public function __construct(\Pubnub\Pubnub $connection) {
		$this->connection = $connection;
		$this->channels = new \ArrayObject();
	}

	public function getConnection() {
		return $this->connection;
	}

	public function addChannel($channel) {
		$this->channels->append($channel);

	}

	public function getChannels() {
		return $this->channels;
	}

}