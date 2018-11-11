<?php
/**
 * Wavesnode REST API wrapper
 * Superclass for raw (custom) REST-API request
 *
 * @author     Vladimir Vyatkin <voldemarpro@hotmail.com>
 * @copyright  Copyright (c) 2017-2018 Vladimir Vyatkin
 * @link       https://github.com/voldemarpro/WavesPhpAPI
 * @see        https://github.com/wavesplatform/Waves
 */

namespace Wavesapi;

class Request {
	
	use Crypto, Base58;
	
	const EXPORT = 0x01;
	
	/**
	 * @var string POST/PUT data
	 */	
	public $data;

	/**
	 * @var string Path to request (optionally with query string)
	 */	
	public $uri;	

	protected $publicKey;
	protected $privateKey;
	
	/**
	 * @var string Rest-api key
	 */
	protected static $apiKey;
	
	/**
	 * @var string Request export flag
	 */
	protected static $export = false;
	
	/**
	 * @var string Host for node rest-api
	 */	
	public static $host;

	public function __construct($conf = []) {
		if (!empty($conf['host']))
			self::$host = $conf['host'];
		if (!empty($conf['api_key']))
			self::$apiKey = $conf['api_key'];
		
		if (!empty($conf['flags'])) {
			if (0x01 & $conf['flags'])
				self::$export = true;
		}		
	}	
	
	public function setCredentials($publicKey = '', $privateKey = '') {
		if (!$publicKey)
			throw new \Exception('Empty public key');
		else
			$this->publicKey = $publicKey;
		
		$this->privateKey = $privateKey;
	}
	
	public function sign($data = '') {
		if (!preg_match('|^[A-Za-z0-9]{32,}$|', $this->publicKey))
			throw new \Exception('Incorrect public key');
		elseif (!preg_match('|^[A-Za-z0-9]{32,}$|', $this->privateKey))
			throw new \Exception('Incorrect private key');		

		return self::to58( $this->sign25519($data, self::from58($this->privateKey)) );
	}
	
	/**
	 * Performs request and returns the JSON-decoded response (string|StdObject|null)
	 * 
	 * @param string $type 'GET' || 'POST'
	 * @param string $uri Path or resource
	 * @param array $headers 
	 * 
	 * @return string|null
	 */
	protected function execute($type = 'GET', $uri = '', $headers = []) {
		$extraHeaderStr = '';
		if ($headers && is_array($headers)) {
			foreach ($headers as $name=>$val)
				$extraHeaderStr .= "--header '$name: $val' ";
		}
		
		$dataStr = '';
		if ($this->data)
			$dataStr = " --data '{$this->data}' ";
		
		$post301 = ($type == 'POST' ? ' --post301' : '');
		$cmd = "curl -L$post301 -X $type --max-time 2 --header 'Accept: application/json' $extraHeaderStr$dataStr".static::$host."$uri";

		if (self::$export) {
			return [
				'type' => $type,
				'headers' => array_merge(['Accept'=>'application/json'], $headers),
				'host' => static::$host,
				'uri' => $uri,
				'data' => $this->data
			];
			
		} else
			return json_decode( shell_exec($cmd) );
	}
	
	/**
	 * @see self::execute
	 */	
	public function get($uri = '', $headers = []) {
		if (!$uri)
			$uri = $this->uri;
		return $this->execute('GET', $uri, $headers);
	}
	
	/**
	 * @see self::execute
	 */
	public function post($uri = '', $headers = []) {
		if (!$uri)
			$uri = $this->uri;	
		return $this->execute('POST', $uri, $headers);
	}
}
?> 