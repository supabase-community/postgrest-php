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
		$url = Url::fromString('https://some_reference_id.supabase.co/rest/v1/countries');
		$this->transform = new PostgrestTransform($url);
	}

	public function testSelect()
	{
		$result = $this->transform->select();
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/countries?select=%2A', (string) $result->url);
	}
}
