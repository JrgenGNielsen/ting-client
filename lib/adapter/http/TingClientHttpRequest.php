<?php

require_once dirname(__FILE__).'/../../exception/TingClientException.php';

class TingClientHttpRequest
{
	const GET = 'GET';
	const POST = 'POST';
	static public $METHODS = array(self::GET,
																	self::POST);
	
	private $method;
	private $baseUrl;
	private $parameters = array(self::GET => array(), 
															self::POST => array());
	
	public function setMethod($method)
	{
		$this->validateMethod($method);
		$this->method = $method;
	}
	
	public function setBaseUrl($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}

	public function setParameter($method, $name, $value)
	{
		$this->validateMethod($method);
		$this->parameters[$method][$name] = $value;
	}
	
	public function setGetParameter($name, $value)
	{
		$this->setParameter(self::GET, $name, $value);
	}
	
	public function setPostParameter($name, $value)
	{
		$this->setParameter(self::POST, $name, $value);
	}
	
	public function setParameters($method, $array)
	{
		$this->validateMethod($method);
		$this->parameters[$method] = array_merge($this->parameters[$method], $array);
	}
	
	public function getMethod()
	{
		return $this->method;
	}

	public function getBaseUrl()
	{
		return $this->baseUrl;
	}
	
	public function getUrl()
	{
		//http_build_query expects values in ISO-8859-1 so decode ut8_decode
		//TODO: Assumes UTF8 input. Add check to test parameter encoding
    $parameters = $this->getGetParameters();
    foreach ($parameters as &$p)
    {
      $p = (!is_array($p)) ? utf8_decode($p) : $p;
    }
		return $this->getBaseUrl().'?'.http_build_query($parameters, NULL, '&');
	}
	
	public function getParameters($method)
	{
		return $this->parameters[$method];
	}
	
	public function getGetParameters()
	{
		return $this->getParameters(self::GET);
	}
	
	public function getPostParameters()
	{
		return $this->getParameters(self::POST);
	}
	
	private function validateMethod($method)
	{
		if (!in_array($method, self::$METHODS))
		{
			throw new TingClientException('Unrecognized method "'.$method.'"');
		}
	}
	
}
