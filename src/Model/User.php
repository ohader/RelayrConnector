<?php
namespace OliverHader\RelayrConnector\Model;

use \OliverHader\RelayrConnector as Relayr;

class User {

	protected $app;
	protected $id;
	protected $name;
	protected $email;

	public function __construct(App $app, $id, $name, $email) {
		$this->app = $app;
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
	}

	public function getApp() {
		return $this->app;
	}

	public function getUserId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getEmail() {
		return $this->email;
	}

}