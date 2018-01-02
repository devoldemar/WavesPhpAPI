<?php
namespace Wavesapi;

class Transfer extends DataModel {
    
	/**
     * @var int Transaction type byte (0x04) used in the original blockchain
     */
	protected $txType = 4;
	
	public $assetId;
	public $senderPublicKey;
	public $recipient;
	public $amount;
	public $fee;
	public $feeAssetId;
	public $timestamp;
	public $attachment;
    
	/**
     * @inheritDoc
     */	
	public function formats() {
		return [
			['txType', 'uInt8', 'base58'=>false],
			['senderPublicKey', 'bytes', 'base58'=>true],
			['assetId', 'assetId', 'base58'=>true],
			['feeAssetId', 'assetId', 'base58'=>true],
			['timestamp', 'uInt64', 'base58'=>false],
			['amount', 'uInt64', 'base58'=>false],
			['fee', 'uInt64', 'base58'=>false],
			['recipient', 'bytes', 'base58'=>true],		
			['attachment', 'bytesWithSize', 'base58'=>true]
		];
	}
}
?> 