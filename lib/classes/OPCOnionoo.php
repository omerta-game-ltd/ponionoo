<?php

namespace Omerta\Ponionoo\Classes;

use Omerta\Ponionoo\Classes\OPCApi;

/**
 * Onionoo client
 * 
 * @see https://onionoo.torproject.org/protocol.html
 *
 * @author Bortoli German for Omerta Game Ltd <support@omertagame.co.uk>
 */
class OPCOnionoo {

	/**
	 * base api path
	 */
	const API_URL = 'https://onionoo.torproject.org/';

	/**
	 * returns a summary document
	 * 
	 * @see https://onionoo.torproject.org/protocol.html#summary
	 */
	CONST PATH_SUMMARY = 'summary';

	/**
	 * Returns a details document
	 * 
	 * @see https://onionoo.torproject.org/protocol.html#details
	 */
	CONST PATH_DETAILS = 'details';

	/**
	 * Returns a bandwidth document
	 * 
	 * @see https://onionoo.torproject.org/protocol.html#bandwidth
	 */
	CONST PATH_BANDWIDTH = 'bandwidth';

	/**
	 * Returns a weights document
	 * 
	 * @see https://onionoo.torproject.org/protocol.html#weights
	 */
	CONST PATH_WEIGHTS = 'weights';

	/**
	 * Returns a clients document
	 * 
	 * @see https://onionoo.torproject.org/protocol.html#clients
	 */
	CONST PATH_CLIENTS = 'clients';

	/**
	 * Returns a uptime document
	 * 
	 * @see https://onionoo.torproject.org/protocol.html#uptime
	 */
	CONST PATH_UPTIME = 'uptime';

	/**
	 * The base api path
	 * 
	 * @var string 
	 */
	protected $base_path = NULL;

	/**
	 * The api object to query post and data.
	 * 
	 * @var OPCApi
	 */
	protected $oApi = NULL;

	/**
	 * Array of cached queries
	 * 
	 * @var array
	 */
	protected $cachedQuery = array();

	/**
	 * Count the quantity of api calls
	 * 
	 * @var integer
	 */
	public $api_calls = 0;

	/**
	 * Construct the api object.
	 * 
	 * @param string $base_path
	 */
	public function __construct($base_path = NULL) {
		if (is_null($base_path)) {
			$this->base_path = self::API_URL;
		} else {
			$this->base_path = filter_var($base_path, FILTER_SANITIZE_URL);
		}

		$this->oApi = new OPCApi();
	}

	/**
	 * Get the tor information for a given ip
	 * 
	 * @param string $ip
	 * @return array
	 * 
	 * @throws \Exception
	 */
	public function getTorInformationByIp($ip = NULL) {
		if (is_null($ip)) {
			$ip = $this->getRealIp();
		}

		if (empty($ip)) {
			throw new \Exception("Unable to get the IP");
		}

		return json_decode($this->queryApi(self::PATH_DETAILS, array('search' => $ip)), TRUE);
	}

	/**
	 * Validates if thor is enabled
	 * 
	 * @param string $ip
	 * @return boolean
	 * @throws \Exception
	 */
	public function isTorEnabled($ip = NULL) {

		try {
			$result = $this->getTorInformationByIp($ip);
		} catch (\Exception $exc) {
			throw $exc;
		}

		if (empty($result['relays'])) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Get the real ip of the user
	 * 
	 * @return string
	 * 
	 */
	public function getRealIp() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public function getFromCache($hash) {
		if (isset($this->cachedQuery[$hash])) {
			return $this->cachedQuery[$hash];
		}

		return FALSE;
	}

	public function setFromCache($hash, $result) {
		$this->cachedQuery[$hash] = $result;
	}

	/**
	 * Query an API path
	 * 
	 * @param string $path
	 * @param array $data
	 * @return string
	 */
	public function queryApi($path, $data = array()) {

		$url = $this->base_path . $path;
		$hash = md5(serialize($data + array('opc_url' => $url)));

		$cached = $this->getFromCache($hash);
		if ($cached) {
			return $cached;
		}

		$this->oApi->setUrl($url);

		$result = $this->oApi->get($data);

		$this->api_calls += 1;

		$this->setFromCache($hash, $result);

		return $result;
	}

}
