<?php
namespace Wavesapi;

class Request {
	
	use Crypto, Base58;
	
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
     * @var string Host for node rest-api
     */	
	public static $host;

	public function __construct($conf = []) {
		if (!empty($conf['host']))
			self::$host = $conf['host'];
		if (!empty($conf['api_key']))
			self::$apiKey = $conf['api_key'];
	}	
	
	public function setCredentials($publicKey = '', $privateKey = '') {
		if (!$publicKey)
			throw new \Exception('Empty public key');
		else
			$this->publicKey = $publicKey;
		
		$this->privateKey = $privateKey;
	}
	
	public function sign($data = '') {
		if ($this->privateKey) {
			return self::to58( $this->sign25519($data, self::from58($this->privateKey)) );
		} else
			throw new \Exception('Empty private key');
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
		
		$cmd = "curl -X $type --header 'Accept: application/json' $extraHeaderStr$dataStr".static::$host."$uri";

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