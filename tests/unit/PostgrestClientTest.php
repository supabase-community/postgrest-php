<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Supabase\Postgrest\PostgrestClient;

final class PostgrestClientTest extends TestCase
{
	private $client;

	public function setup(): void
	{
		parent::setUp();
		$this->client = new PostgrestClient('some_reference_id', 'some_api_key');
	}

	public function testFrom(): void
	{
		$result = $this->client->from('users');
		$this->assertSame('https://some_reference_id.supabase.co/rest/v1/users', (string) $result->url);
	}

	public function testCanRPC(): void
	{
		$result = $this->client->rpc('add_one_each', ['arr'=> [1, 2, 3]], ['opts'=>'head']);
		$this->assertSame('https://some_reference_id.supabase.co/rest/v1/rpc/add_one_each', (string) $result->url);
	}
}
