<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Spatie\Url\Url;
use Supabase\Postgrest\PostgrestTransform;

final class PostgrestTransformTest extends TestCase
{
	private $transform;

	public function setup(): void
	{
		parent::setUp();
		$url = Url::fromString('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/');
		$this->transform = new PostgrestTransform($url);
	}

	public function testSelect()
	{
		$result = $this->transform->select();
// @TODO - this is truly failing - should include the select portion the url result
		$this->assertEquals('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/?select=%2A', (string) $result->url);
	}
}
