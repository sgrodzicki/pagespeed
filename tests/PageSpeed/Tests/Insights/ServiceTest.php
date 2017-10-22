<?php

namespace PageSpeed\Tests\Insights;

use PageSpeed\Insights\Service;
use PageSpeed\Tests\PageSpeedTestCase;

class ServiceTest extends PageSpeedTestCase
{
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidUrl()
	{
		$pageSpeed = new Service();
		$pageSpeed->getResults('localhost');
	}

	public function testResults()
	{
		$pageSpeed = new Service();
		$url       = 'https://github.com/sgrodzicki/pagespeed';
		$results   = $pageSpeed->getResults($url);

		$keys = array('kind', 'id', 'responseCode', 'title', 'ruleGroups', 'pageStats', 'formattedResults', 'version');
		foreach ($keys as $key) {
			$this->assertArrayHasKey($key, $results);
		}

		$this->assertEquals($url, $results['id']);
		$this->assertEquals(200, $results['responseCode']);
		$this->assertEquals('GitHub - sgrodzicki/pagespeed: A PHP library to interact with the PageSpeed Insights API', $results['title']);
		$this->assertTrue(is_array($results['pageStats']));
		$this->assertTrue(is_array($results['formattedResults']));
		$this->assertTrue(is_array($results['version']));
		$this->assertEquals(1, $results['version']['major']);
	}
}
