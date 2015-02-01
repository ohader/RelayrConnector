<?php
namespace OliverHader\RelayrConnector\Handler;

use \OliverHader\RelayrConnector as Relayr;

class ChainedHandler implements HandlerInterface {

	/**
	 * @var array|HandlerInterface[]
	 */
	protected $handlers = array();

	/**
	 * @param HandlerInterface $handler
	 * @return ChainedHandler
	 */
	public function register(HandlerInterface $handler) {
		if (in_array($handler, $this->handlers)) {
			throw new \RuntimeException('Handler is already registered');
		}
		$this->handlers[] = $handler;
		return $this;
	}

	/**
	 * @param array $envelope
	 * @return bool
	 */
	public function update($envelope) {
		foreach ($this->handlers as $handler) {
			$result = $handler->update($envelope);
			if ($result !== TRUE) {
				return FALSE;
			}
		}
		return TRUE;
	}

}