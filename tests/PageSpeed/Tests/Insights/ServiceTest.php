<?php

namespace PageSpeed\Tests\Insights;

class ServiceTest extends \PageSpeed\Tests\PageSpeedTestCase
{
	private $key;

	protected function setUp()
	{
		global $key;

		$this->key = $key;

		parent::setUp();
	}

	public function testApiKey()
	{
		if (is_null($this->key)) {
			$this->markTestSkipped('API key missing');
		}
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidApiKey()
	{
		new \PageSpeed\Insights\Service('invalid_api_key');
	}

	/**
	 * @depends           testApiKey
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidUrl()
	{
		$pageSpeed = new \PageSpeed\Insights\Service('AIzaSyD8jdNujwZY81cmmX_DOEDrezLG20WrtNc');
		$pageSpeed->getResults('localhost');
	}

	/**
	 * @depends testApiKey
	 */
	public function testResults()
	{
		$pageSpeed = new \PageSpeed\Insights\Service('AIzaSyD8jdNujwZY81cmmX_DOEDrezLG20WrtNc');
		$url       = 'https://github.com/sgrodzicki/PageSpeed';
		$results   = $pageSpeed->getResults($url);

		$keys = array('kind', 'id', 'responseCode', 'title', 'score', 'pageStats', 'formattedResults', 'version');
		foreach ($keys as $key) {
			$this->assertArrayHasKey($key, $results);
		}

		$this->assertEquals($url, $results['id']);
		$this->assertEquals(200, $results['responseCode']);
		$this->assertStringStartsWith('sgrodzicki/PageSpeed', $results['title']);
		$this->assertStringEndsWith('GitHub', $results['title']);
		$this->assertTrue(is_array($results['pageStats']));
		$this->assertTrue(is_array($results['formattedResults']));
		$this->assertTrue(is_array($results['version']));
		$this->assertEquals(1, $results['version']['major']);
	}
}
