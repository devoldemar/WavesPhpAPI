<?php
namespace Wavesapi;

class Assets extends Request {
	
	public function __construct($conf = array()) {
		parent::__construct($conf);
		$this->uri = '/assets';
	}
	
    /**
     * Balances for all assets that the given account ever had (besides WAVES)
     *
     * @return object
     */
	public function getBalance($addr = '') {
		return $this->get("{$this->uri}/balance/$addr");
	}
	
    /**
     * Account's balance for the given asset
	 *
     * @param string $addr Address in base58-encoded format
     * @param string $assetId AssetId in base58-encoded format
	 *
     * @return object
     */
	public function getBalanceForId($addr = '', $assetId = '') {
		return parent::get("{$this->uri}/balance/$addr/$assetId");
	}
	
    /**
     * Asset balance distribution by account
     *
     * @return object
     */
	public function getDistribution($assetId = '') {
		return parent::get("{$this->uri}/$assetId/distribution");
	}
	
    /**
     * Publish signed asset transfer transaction to the Blockchain
     *
     * @return object
     */
	public function broadcastTransfer(array $params) {
		$model = new Transfer($params);
		
		$arr = $model->toArray();
		$arr['signature'] = $this->sign($model->getDataBytes());

		$this->data = json_encode($arr);
		
		return parent::post("{$this->uri}/broadcast/transfer", [
			'Content-type'=>'application/json'
		]);
	}
}
?> 