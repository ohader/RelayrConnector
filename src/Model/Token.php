<?php
namespace OliverHader\RelayrConnector\Model;

use \OliverHader\RelayrConnector as Relayr;

class Token {

	protected $app;
	protected $id;
	protected $bearer;

	public function __construct(App $app, $id, $bearer) {
		$this->app = $app;
		$this->id = $id;
		$this->bearer = $bearer;
	}

	public function getApp() {
		return $this->app;
	}

	public function getId() {
		return $this->id;
	}

	public function getBearer() {
		return $this->bearer;
	}

}