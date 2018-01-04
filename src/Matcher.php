<?php
/**
 * Wavesnode API request wrapper for decentralized exhange (matcher)
 *
 * @author     Vladimir Vyatkin <voldemarpro@hotmail.com>
 * @copyright  Copyright (c) 2017-2018 Vladimir Vyatkin
 * @link       https://github.com/voldemarpro/WavesPhpAPI
 * @see        https://github.com/wavesplatform/Waves
 */

namespace Wavesapi;

class Matcher extends Request {
	
	/**
	 * @var string Host for matcher
	 */	
	public static $host;

	public function __construct($conf = array()) {
		if (!empty($conf['host'])) {
			self::$host = $conf['host'];
			$conf['host'] = null;
		}
		parent::__construct($conf);
		$this->uri = '/matcher';
	}
	
	/**
	 * Matcher's public key
	 *
	 * @return object
	 */ 
	public function getPublicKey() {
		return $this->get();
	}
	
	/**
	 * Price-amount orderbook (depth of market) for the given asset pair
	 *
	 * @return object
	 */
	public function getDOM($amountAsset = null, $priceAsset = null) {
		if ($amountAsset == null)
			throw new \Exception('Amount asset may not be empty');
		if ($priceAsset == null)
			$priceAsset = 'WAVES';		

		return $this->get("{$this->uri}/orderbook/$amountAsset/$priceAsset");
	}
	
	/**
	 * Order history for a given public key
	 *
	 * @return array
	 */
	public function getHistoryByPublicKey() {
		$timestamp = time() * 1000;
		$signature = $this->sign(self::from58($this->publicKey) . pack('J', $timestamp));

		return $this->get("{$this->uri}/orderbook/{$this->publicKey}", [
			'Timestamp' => (string)$timestamp,
			'Signature' => $signature
		]);
	}	
	
	/**
	 * Post limit order to DEX
	 *
	 * @return object
	 */
	public function createOrder(array $params) {
		$model = new Order($params);
		$model->senderPublicKey = $this->publicKey;
		
		$arr = $model->toArray();
		$arr['signature'] = $this->sign($model->getDataBytes());

		$this->data = json_encode($arr);
		
		return parent::post("{$this->uri}/orderbook", [
			'Content-type'=>'application/json'
		]);
	}
	
	/**
	 * Cancel previously created limit order
	 *
	 * @return object
	 */
	public function cancelOrder(array $params) {
		if (!isset($params['amountAsset']) || !isset($params['priceAsset']))
			throw new \Exception('Undefined amount and/or price asset');
		
		if (empty($params['priceAsset']))
			$params['priceAsset'] = 'WAVES';
		
		$model = new OrderRollback($params);

		$arr = $model->toArray();
		$arr['signature'] = $this->sign($model->getDataBytes());

		$this->data = json_encode($arr);
		
		return parent::post("{$this->uri}/orderbook/{$params['amountAsset']}/{$params['priceAsset']}/cancel", [
			'Content-type'=>'application/json'
		]);
	}
}
?> 