<?php
/**
 * Arbitrary data model for Wavesnode REST API wrapper
 * Used to construct request body with signature
 *
 * @author     Vladimir Vyatkin <voldemarpro@hotmail.com>
 * @copyright  Copyright (c) 2017-2018 Vladimir Vyatkin
 * @link       https://github.com/voldemarpro/WavesPhpAPI
 * @see        https://github.com/wavesplatform/Waves
 */
 
namespace Wavesapi;

class DataModel {
	
	use Base58;

	public $signature;
    
	/**
	 * Specifying formats for all attributes
	 * Order of attributes is used for signature calculation 
	 */	
	public function formats() {
		//example: ['timestamp', 'uInt64', 'base58'=>false]
		return [];
	}
	
	public function __construct($params) {
		foreach ($this->formats() as $f) {
			if (isset( $params[$f[0]] )) {
				$this->$f[0] = $params[$f[0]];
			}	
		}
	}

	/**
	 * Calculate data bytes
	 *
	 * @return binary
	 */
	public function getDataBytes() {
		$data = '';
		foreach ($this->formats() as $row) {
			$value = $this->$row[0];
			
			if ($row['base58']) {
				$value = $value ? self::from58($value) : '';
			}
			
			switch ($row[1]) {
				case 'uInt64':
					$data .= pack('J', $value);
					break;
				
				case 'uInt32':
					$data .= pack('N', $value);
					break;
				
				case 'uInt16':
					$data .= pack('n', $value);
					break;
					
				case 'uInt8':
					$data .= pack('C', $value);
					break;
				
				case 'assetId':
					if (empty($value) || $value == 'WAVES') {
						$data .= pack('C', 0);
					} else {
						$data .= (pack('C', 1) . $value);
					}
					break;
					
				case 'bytesWithSize':
					$data .= (pack('n', strlen($value)) . $value);
					break;
				
				case 'orderType':
					$data .= ($value == 'sell' ? pack('C', 1) : pack('C', 0));
					break;
					
				case 'bytes':
				default:
					$data .= $value;
					break;
			}
		}
		
		return $data;
	}
	
	public function toArray() {
		$arr = [];
		foreach ($this->formats() as $f) {
			$arr[$f[0]] = $this->$f[0];
		}
		return $arr;
	}
}
?> 