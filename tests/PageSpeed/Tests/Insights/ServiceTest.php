<?php

namespace PageSpeed\Tests\Insights;

class ServiceTest extends \PageSpeed\Tests\PageSpeedTestCase
{
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidUrl()
	{
		$pageSpeed = new \PageSpeed\Insights\Service();
		$pageSpeed->getResults('localhost');
	}

	public function testResults()
	{
		$pageSpeed = new \PageSpeed\Insights\Service();
		$url       = 'https://github.com/sgrodzicki/pagespeed';
		$results   = $pageSpeed->getResults($url);

		$keys = array('kind', 'id', 'responseCode', 'title', 'ruleGroups', 'pageStats', 'formattedResults', 'version');
		foreach ($keys as $key) {
			$this->assertArrayHasKey($key, $results);
		}

		$this->assertEquals($url, $results['id']);
		$this->assertEquals(200, $results['responseCode']);
		$this->assertStringStartsWith('sgrodzicki/pagespeed', $results['title']);
		$this->assertStringEndsWith('GitHub', $results['title']);
		$this->assertTrue(is_array($results['pageStats']));
		$this->assertTrue(is_array($results['formattedResults']));
		$this->assertTrue(is_array($results['version']));
		$this->assertEquals(1, $results['version']['major']);
	}
}
