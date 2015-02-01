<?php
namespace OliverHader\RelayrConnector\Repository;

use \OliverHader\RelayrConnector as Relayr;

class ModelRepository {

	/**
	 * @var ModelRepository
	 */
	static protected $instance;

	/**
	 * @return ModelRepository
	 */
	static public function getInstance() {
		if (!isset(static::$instance)) {
			static::$instance = new ModelRepository();
		}
		return static::$instance;
	}

	protected $models;

	public function findAll() {
		if (!isset($this->models)) {
			$this->models = Relayr\Service\RelayrService::getInstance()->getModels();
		}
		return $this->models;
	}

	/**
	 * @param string $id
	 * @return NULL|Relayr\Model\Model
	 */
	public function findById($id) {
		$this->findAll();
		if (isset($this->models[$id])) {
			return $this->models[$id];
		}
		return NULL;
	}

}