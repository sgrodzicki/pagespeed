<?php

namespace PageSpeed\Insights;

use PageSpeed\Insights\Exception\InvalidArgumentException;
use PageSpeed\Insights\Exception\RuntimeException;

class Service
{
	/**
	 * Google API key
	 *
	 * @var string
	 * @link https://code.google.com/apis/console#access
	 */
	private $key;

	/**
	 * @var string
	 */
	private $gateway = 'https://www.googleapis.com/pagespeedonline/v2';

	/**
	 * @return Service
	 */
	public function __construct()
	{
		return $this;
	}

	/**
	 * Returns PageSpeed score, page statistics, and PageSpeed formatted results for specified URL
	 *
	 * @param string $url
	 * @param string $locale
	 * @param string $strategy
	 * @param optional array $extraParams
	 * @return array
	 * @throws Exception\InvalidArgumentException
	 * @throws Exception\RuntimeException
	 */
	public function getResults($url, $locale = 'en_US', $strategy = 'desktop', array $extraParams = null)
	{
		if (0 === preg_match('#http(s)?://.*#i', $url)) {
			throw new InvalidArgumentException('Invalid URL');
		}

		$client = new \Guzzle\Service\Client($this->gateway);

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->get('runPagespeed');
		$request->getQuery()
			->set('prettyprint', false) // reduce the response payload size
			->set('url', $url)
			->set('locale', $locale)
			->set('strategy', $strategy);

		if (isset($extraParams)) {
			$query = $request->getQuery();
			foreach($extraParams as $key=>$value)
			{
				$query[$key] = $value;
			}
		}

		try {
			$response = $request->send();
			$response = $response->getBody();
			$response = json_decode($response, true);

			return $response;
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
			$response = $e->getResponse();
			$response = $response->getBody();
			$response = json_decode($response);

			throw new RuntimeException($response->error->message, $response->error->code);
		}
	}
}
