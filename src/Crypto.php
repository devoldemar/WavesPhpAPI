<?php
/**
 * @see https://github.com/wavesplatform/Waves
 */
namespace Wavesapi;

trait Crypto {
	public static function keccak256($bytes) {
		if (class_exists('\Keccak\Keccak256') && $bytes !== null)
			return \Keccak\Keccak256::hash($bytes, 256, true);	
		else
			return null;
	}
	
	public static function blake2b256($bytes) {
		if (function_exists('\blake2b') && $bytes !== null)
			return \blake2b($bytes, 32, null, true);	
		else
			return null;
	}	
	
	/**
	 * @param binary $data  
	 * @param binary $privateKey 
	 * 
	 * @return binary
	 */
	public function sign25519($data = '', $privateKey) {
		if (function_exists('\curve25519_sign') && $data) {
			if (function_exists('\openssl_random_pseudo_bytes'))
				$secureRandom = \openssl_random_pseudo_bytes(64);
			elseif (function_exists('\random_bytes'))
				$secureRandom = \random_bytes(64);
			else
				return null;

			return \curve25519_sign($secureRandom, $privateKey, $data);	
		
		} else
			return null;
	}
	
	/**
	 * @param binary $seed 
	 * 
	 * @return array
	 */
	public function keypair25519($seed) {
		if (function_exists('\curve25519_private')) {
			if (!$seed) {
				if (function_exists('\openssl_random_pseudo_bytes'))
					$seed = \openssl_random_pseudo_bytes(64);
				elseif (function_exists('\random_bytes'))
					$seed = \random_bytes(64);
				else
					return null;
			}
			
			$privateKey = \curve25519_private($seed);
			$publicKey  = \curve25519_public($privateKey);
			
			return ['publicKey'=>$publicKey, 'privateKey'=>$privateKey];
		
		} else
			return null;
	}
}
?> 