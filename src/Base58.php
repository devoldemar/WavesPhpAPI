<?php
/**
 * @see https://github.com/StephenHill/Base58
 */
namespace Wavesapi;

trait Base58 {
    
	/**
     * @var object Base58 encoder/decoder
     */	
	public static $base58;
	
	private static function check58() {
		if (self::$base58 == null) {
			if (!class_exists('\StephenHill\Base58'))
				return false;			
			else {
				self::$base58 = new \StephenHill\Base58();
				return true;
			}
		} else
			return true;
	}
	
	public static function from58($string = '') {
		if (!self::check58()) {
			return null;				
		}
		return self::$base58->decode($string);
	}

	public static function to58($bytes) {
		if (!self::check58()) {
			return null;				
		}
		return self::$base58->encode($bytes);
	}
}
?> 