<?php
namespace Wavesapi;

class Transaction extends Request {
	
	public function __construct($conf = array()) {
		parent::__construct($conf);
		$this->uri = '/transactions';
	}

    /**
     * @return object Transaction data by transaction ID
     */
	public function getById($id = '') {
		return parent::get("{$this->uri}/info/$id");
	}
	
    /**
     * Return the specified number of the latest transactions by the given account address
	 *
     * @param string $addr Address in base58-encoded format
     * @param int $limit Number of transaction to return, max = 50
	 *
	 * @return array
     */
	public function getByAccount($addr = '', $limit = 50) {
		return parent::get("{$this->uri}/address/$addr/limit/$limit");
	}
}
?>