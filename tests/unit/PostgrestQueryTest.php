<?php

declare(strict_types=1);
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

require __DIR__.'/../../src/PostgrestFilter.php';

final class PostgrestQueryTest extends TestCase
{
	private $query;
	public $url;

	public function setup(): void
	{
		parent::setUp();
		\Dotenv\Dotenv::createImmutable(__DIR__);
		$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__, '/../../.env.test');
		$dotenv->load();
		$api_key = getenv('API_KEY');
		$reference_id = getenv('REFERENCE_ID');
		$this->query = new PostgrestQuery($reference_id, $api_key, $opts = []);
	}

	public function testSelect()
	{
		$result = $this->query->select();
		print_r($result);
		ob_flush();
	}

	public function testInsert()
	{
		$result = $this->query->insert('countries');
		print_r((string) $result->url);
		print_r($result);
		ob_flush();
		assertEquals('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/', (string) $result->url);
	}

	public function testUpsert()
	{
		$result = $this->query->upsert('countries');
		print_r((string) $result->url);
		print_r($result);
		ob_flush();
		assertEquals('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/', (string) $result->url);
	}

	public function testUpdate()
	{
		$result = $this->query->update('countries');
		print_r((string) $result->url);
		print_r($result);
		ob_flush();
		assertEquals('gpdefvsxamnscceccczu', (string) $result->url);
	}

	public function testDelete()
	{
		$result = $this->query->delete('countries');
		print_r((string) $result->url);
		print_r($result);
		ob_flush();
		assertEquals('gpdefvsxamnscceccczu', (string) $result->url);
	}
}
