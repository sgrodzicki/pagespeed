<?php

namespace PageSpeed\Insights;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PageSpeed\Insights\Exception\InvalidArgumentException;
use PageSpeed\Insights\Exception\RuntimeException;

class Service
{
	/**
	 * @var string
	 */
	private $gateway = 'https://www.googleapis.com/pagespeedonline/v2/';

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

		$client = new Client(['base_uri' => $this->gateway]);
        $query = [
            'url' => $url,
            'locale' => $locale,
            'strategy' => $strategy,
        ];

        if (!empty($extraParams)) {
            foreach($extraParams as $key => $value) {
                $query[$key] = $value;
            }
        }

        try {

            /** @var \GuzzleHttp\Psr7\Response $response */
		    $response = $client->request('GET','runPagespeed', ['query' => $query]);
            $response = $response->getBody()->getContents();
            /** @var array $response */
            $response = json_decode($response, true);

            return $response;

		} catch (RequestException $e) {
			$response = $e->getResponse();
			$response = $response->getBody();
			/** @var \stdClass $response */
			$response = json_decode($response);
			throw new RuntimeException($response->error->message, $response->error->code);
		}
	}
}
