<?php
namespace Wavesapi;

class Address extends Request {
	
	public function __construct($conf = array()) {
		parent::__construct($conf);
		$this->uri = '/addresses';
	}
	
    /**
     * Generate a new account address in the node's wallet
     */
	public function create() {
		return parent::post(false, ['api_key'=>self::$apiKey]);
	}	

    /**
     * Get list of all accounts addresses in the node's wallet
     *
     * @return array
     */
	public function getList() {
		return parent::get();
	}

    /**
     * Get account balance in WAVES by {address} after {confirmations}
     *
     * @return object
     */
	public function getBalance($addr = '', $confirmations = 1) {
		return parent::get("{$this->uri}/balance/$addr/$confirmations");
	}
}
?> 