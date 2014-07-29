<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Omerta\Ponionoo\Interfaces;

/**
 * Interface to implement the api
 *
 * @author Bortoli German for Omerta Game Ltd <support@omertagame.co.uk>
 */
interface OPIApi {

	/**
	 * Set the current URL
	 * 
	 * @param string $url
	 */
	public function setUrl($url);

	/**
	 * Get a result from GET method
	 * 
	 * @param array $data current data to send
	 * 
	 * @return string
	 * 
	 * @throws Exception if fails
	 */
	public function get($data = array());

	/**
	 * Get a result from POST method
	 * 
	 * @param array $data current data to send
	 * 
	 * @return string
	 * 
	 * @throws Exception if fails
	 */
	public function post($data = array());

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
	public function getCurl($request_method = 'GET', $data = array());
	
	/**
	 * Set the headers to send via curl
	 * 
	 * @param array $headers the headers options
	 * 
	 */
	public function setHeaders($headers = array());
	
	/**
	 * Set the options to send to curl
	 * 
	 * @param array $opts
	 */
	public function setOpts($opts = array());
	
	/**
	 * Get the URL
	 */
	public function getUrl();
}
