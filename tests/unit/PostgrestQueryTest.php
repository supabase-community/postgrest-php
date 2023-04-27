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
		$url = Url::fromString('https://some_reference_id.supabase.co/rest/v1/');
		$this->query = new PostgrestQuery($url);
	}

	public function testSelect()
	{
		$result = $this->query->select();
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?select=%2A', (string) $result->url);
	}

	public function testInsert()
	{
		$result = $this->query->insert('countries');
		ob_flush();
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/', (string) $result->url);
	}

	public function testUpsert()
	{
		$result = $this->query->upsert('countries');
		ob_flush();
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/', (string) $result->url);
	}

	public function testUpdate()
	{
		$result = $this->query->update('countries');
		ob_flush();
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/', (string) $result->url);
	}

	public function testDelete()
	{
		$result = $this->query->delete('countries');
		ob_flush();
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/', (string) $result->url);
	}
}
