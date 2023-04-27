<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Supabase\Postgrest\PostgrestClient;
use Supabase\Postgrest\Util\EnvSetup;

final class PostgrestTest extends TestCase
{
	private $client;

	public function setup(): void
	{
		parent::setUp();

		$keys = EnvSetup::env(__DIR__.'/../');
		$api_key = $keys['API_KEY'];
		$reference_id = $keys['REFERENCE_ID'];

		$this->client = new PostgrestClient($reference_id, $api_key);
	}

	/**
	 * @dataProvider countryProvider
	 */
	public function testEq($data): void
	{
		$result = $this->client->from('countries')->select()->eq('name', $data)->execute();
		$this->assertEquals('200', $result->status);
		$this->assertEquals('OK', $result->statusText);
		$this->assertCount(3, $result->data[0]);
		$this->assertArrayHasKey('id', $result->data[0]);
		$this->assertArrayHasKey('name', $result->data[0]);
		$this->assertArrayHasKey('created_at', $result->data[0]);
	}

	/**
	 * @dataProvider countryProvider
	 */
	public function testNeq($data): void
	{
		$result = $this->client->from('countries')->select()->neq('name', $data)->execute();
		$this->assertEquals('200', $result->status);
		$this->assertEquals('OK', $result->statusText);
		$this->assertCount(3, $result->data[0]);
		$this->assertArrayHasKey('id', $result->data[0]);
		$this->assertArrayHasKey('name', $result->data[0]);
		$this->assertArrayHasKey('created_at', $result->data[0]);
	}

	/**
	 * @dataProvider idProvider
	 */
	public function testGt($data): void
	{
		$result = $this->client->from('countries')->select()->gt('id', $data)->execute();
		$this->assertEquals('200', $result->status);
		$this->assertEquals('OK', $result->statusText);
		if (isset($result->data[0])) {
			$this->assertCount(3, $result->data[0]);
			$this->assertArrayHasKey('id', $result->data[0]);
			$this->assertArrayHasKey('name', $result->data[0]);
			$this->assertArrayHasKey('created_at', $result->data[0]);
		}
	}

	/**
	 * @dataProvider idProvider
	 */
	public function testGte($data): void
	{
		$result = $this->client->from('countries')->select()->gte('id', $data)->execute();
		$this->assertEquals('200', $result->status);
		$this->assertEquals('OK', $result->statusText);
		$this->assertCount(3, $result->data[0]);
		$this->assertArrayHasKey('id', $result->data[0]);
		$this->assertArrayHasKey('name', $result->data[0]);
		$this->assertArrayHasKey('created_at', $result->data[0]);
	}

	/**
	 * @dataProvider idProvider
	 */
	public function testLessThan($data): void
	{
		$result = $this->client->from('countries')->select()->lt('id', $data)->execute();
		ob_flush();
		$this->assertEquals('200', $result->status);
		$this->assertEquals('OK', $result->statusText);
		$this->assertCount(3, $result->data[0]);
		$this->assertArrayHasKey('id', $result->data[0]);
		$this->assertArrayHasKey('name', $result->data[0]);
		$this->assertArrayHasKey('created_at', $result->data[0]);
	}

	/**
	 * @dataProvider idProvider
	 */
	public function testLte($data): void
	{
		$result = $this->client->from('countries')->select()->lte('id', $data)->execute();
		$this->assertEquals('200', $result->status);
		$this->assertEquals('OK', $result->statusText);
		$this->assertCount(3, $result->data[0]);
		$this->assertArrayHasKey('id', $result->data[0]);
		$this->assertArrayHasKey('name', $result->data[0]);
		$this->assertArrayHasKey('created_at', $result->data[0]);
	}

	public static function countryProvider(): array
	{
		return[
			['Algeria'],
			['Germany'],
			['Indonesia'],
			['Armenia'],
		];
	}

	public static function idProvider(): array
	{
		return[
			['2'],
			['3'],
			['4'],
			['5'],
		];
	}
}
