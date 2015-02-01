<?php
namespace OliverHader\RelayrConnector\Handler;

use \OliverHader\RelayrConnector as Relayr;

interface HandlerInterface {

	/**
	 * @param array $envelope
	 * @return bool TRUE if processing shall continue, FALSE to stop execution
	 */
	public function update($envelope);

}