<?php
namespace Wavesapi;

class OrderRollback extends DataModel {

	public $sender;
	public $orderId;
    
	/**
     * @inheritDoc
     */	
	public function formats() {
		return [
			['sender', 'bytes', 'base58'=>true],
			['orderId', 'bytes', 'base58'=>true]
		];
	}	
}
?> 