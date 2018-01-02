<?php
namespace Wavesapi;

class Order extends DataModel {

	public $senderPublicKey;
	public $matcherPublicKey;
	public $amountAsset;
	public $priceAsset;
	public $orderType;
	public $price;
	public $amount; // normalized to 10^8 precision of waves
	public $timestamp;
	public $expiration;
	public $matcherFee;
    
	/**
     * @inheritDoc
     */	
	public function formats() {
		return [
			['senderPublicKey', 'bytes', 'base58'=>true],
			['matcherPublicKey', 'bytes', 'base58'=>true],
			['amountAsset', 'assetId', 'base58'=>true],
			['priceAsset', 'assetId', 'base58'=>true],
			['orderType', 'orderType', 'base58'=>false],
			['price', 'uInt64', 'base58'=>false],
			['amount', 'uInt64', 'base58'=>false],	
			['timestamp', 'uInt64', 'base58'=>false],
			['expiration', 'uInt64', 'base58'=>false],
			['matcherFee', 'uInt64', 'base58'=>false]
		];
	}
	
	public function toArray() {
		$arr = parent::toArray();
		$arr['assetPair'] = [
			'amountAsset' => $arr['amountAsset'],
			'priceAsset' => $arr['priceAsset']
		];
		
		unset($arr['amountAsset'], $arr['priceAsset']);
		
		return $arr;
	}	
}
?> 