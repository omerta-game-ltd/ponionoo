<?php

namespace Omerta\Ponionoo\Classes;

use Omerta\Ponionoo\Interfaces\OPIApiInterface;
use Curl\Curl;

/**
 * Description of PCurl
 *
 * @author Bortoli German for Omerta Game Ltd <support@omertagame.co.uk>
 */
class OPCApi implements OPIApiInterface {

	protected $url;
	protected $headers;

	/* @var $curl \Curl */
	protected $curl;

	const METHOD_GET = "GET";
	const METHOD_POST = "POST";

	/**
	 * Constructor 
	 * 
	 * @param string $url the url to send the data
	 * @param array $headers the headers to send
	 * 
	 * @param array $opts the curl options to send
	 */
	public function __construct($url = NULL, $headers = array(), $opts = array()) {
		$this->curl = new Curl();

		$this->setUrl($url);
		$this->setHeaders($headers);

		$def_opts = array(
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_CONNECTTIMEOUT => 3,
			CURLOPT_TIMEOUT => 5,
		);

		$opts = $def_opts + $opts;

		$this->setOpts($opts);
	}

	/**
	 * Get a result from GET method
	 * 
	 * @param array $data current data to send
	 * 
	 * @return string
	 * 
	 * @throws Exception if fails
	 */
	public function get($data = array()) {
		return $this->getCurl(self::METHOD_GET, $data);
	}

	/**
	 * Execute and get the method api
	 * 
	 * @param string $request_method method type, POST, GET, PUT, DELETE
	 * 
	 * @param array $data the current data to send
	 * 
	 * @return string
	 * 
	 * @throws Exception if fails
	 */
	public function getCurl($request_method = 'GET', $data = array()) {
		$request_method = strtolower($request_method);
		$this->curl->$request_method($this->getUrl(), $data);
		return $this->curl->response;
	}

	/**
	 * Get a result from POST method
	 * 
	 * @param array $data current data to send
	 * 
	 * @return string
	 * 
	 * @throws Exception if fails
	 */
	public function post($data = array()) {
		return $this->getCurl(self::METHOD_POST, $data);
	}

	/**
	 * Set the current URL
	 * 
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = filter_var($url, FILTER_SANITIZE_URL);
	}

	/**
	 * Set the headers to send via curl
	 * 
	 * @param array $headers the headers options
	 * 
	 */
	public function setHeaders($headers = array()) {
		if (empty($headers)) {
			return FALSE;
		}

		foreach ($headers as $head_key => $head_val) {
			$this->curl->setHeader($head_key, $head_val);
		}
	}

	/**
	 * Set the options to send to curl
	 * 
	 * @param array $opts
	 */
	public function setOpts($opts = array()) {
		if (empty($opts)) {
			return FALSE;
		}

		foreach ($opts as $opt_key => $opt_val) {
			$this->curl->setOpt($opt_key, $opt_val);
		}
	}

	/**
	 * Get the url
	 * 
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

}
