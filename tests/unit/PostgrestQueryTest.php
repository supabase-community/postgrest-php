<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Spatie\Url\Url;
use Supabase\Postgrest\PostgrestQuery;

final class PostgrestQueryTest extends TestCase
{
	private $query;
	public $url;

	public function setup(): void
	{

		parent::setUp();
		$url = Url::fromString('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/');
		$this->query = new PostgrestQuery($url);
	}

	public function testSelect()
	{
		$result = $this->query->select();
		$this->assertEquals('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/?select=%2A', (string) $result->url);
	}

	public function testInsert()
	{
		$result = $this->query->insert('countries');
		print_r((string) $result->url);
		print_r($result);
		ob_flush();
		$this->assertEquals('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/', (string) $result->url);
	}

	public function testUpsert()
	{
		$result = $this->query->upsert('countries');
		print_r((string) $result->url);
		print_r($result);
		ob_flush();
		$this->assertEquals('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/', (string) $result->url);
	}

	public function testUpdate()
	{
		$result = $this->query->update('countries');
		print_r((string) $result->url);
		print_r($result);
		ob_flush();
		$this->assertEquals('gpdefvsxamnscceccczu', (string) $result->url);
	}

	public function testDelete()
	{
		$result = $this->query->delete('countries');
		print_r((string) $result->url);
		print_r($result);
		ob_flush();
		$this->assertEquals('gpdefvsxamnscceccczu', (string) $result->url);
	}
}
