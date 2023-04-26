<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Supabase\Postgrest\PostgrestClient;
use Supabase\Postgrest\Util\EnvSetup;

final class PostgrestClientTest extends TestCase
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

	public function testBulkProcessing(): void
	{
		$result = $this->client->rpc('add_one_each', ['arr'=> [1, 2, 3]])->execute();
		$this->assertEquals('200', $result->status);
		$this->assertEquals('OK', $result->statusText);
	}

	/**
	 * @dataProvider providerArray
	 */
	public function testFetchData($data): void
	{
		$result = $this->client->from('countries')->select()->eq('name', $data)->execute();
		$this->assertEquals('200', $result->status);
		$this->assertEquals('OK', $result->statusText);
		$this->assertCount(3, $result->data[0]);
		$this->assertArrayHasKey('id', $result->data[0]);
		$this->assertArrayHasKey('name', $result->data[0]);
		$this->assertArrayHasKey('created_at', $result->data[0]);
	}

	public function testCanBeCreatedFromValidUrl(): void
	{
		$this->assertInstanceOf(
			PostgrestClient::class,
			new PostgrestClient('http://localhost:3000', [])
		);
	}

	public function testCanSelectTable(): void
	{
		$result = $this->client->from('users')->select();
		ob_flush();
		assertSame($result, 'gpdefvsxamnscceccczu');
	}

	public function testCanSelectColumns(): void
	{
		$this->assertSame($client->from('users')->select('id, name')->url->__toString(), 'http://localhost:3000/users?select=id%2Cname');
	}

	public function testCanRPC(): void
	{
		$client = new PostgrestClient('http://localhost:3000', []);

		$this->assertSame($client->rpc('add_one')->select()->url->__toString(), 'http://localhost:3000/rpc/add_one');
	}

	public function testCanExecute(): void
	{
		$client = new PostgrestClient('http://localhost:3000', []);

		$this->assertSame($client->from('users')->select()->execute()->status, 200);
	}

	public static function providerArray(): array
	{
		return[
			['Algeria'],
			['Germany'],
			['Indonesia'],
			['Armenia'],
		];
	}
}
