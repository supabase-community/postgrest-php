<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Spatie\Url\Url;
use Supabase\Postgrest\PostgrestFilter;

final class PostgrestFilterTest extends TestCase
{
	private $filter;

	public function setup(): void
	{
		parent::setUp();
		$url = Url::fromString('https://some_reference_id.supabase.co/rest/v1/');
		$this->filter = new PostgrestFilter($url);
	}

	public function testEq(): void
	{
		$result = $this->filter->eq('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=eq.Algeria', (string) $result->url);
	}

	public function testNeq(): void
	{
		$result = $this->filter->neq('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=neq.Algeria', (string) $result->url);
	}

	public function testGt(): void
	{
		$result = $this->filter->gt('id', '1');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?id=gt.1', (string) $result->url);
	}

	public function testGte(): void
	{
		$result = $this->filter->gte('id', '1');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?id=gte.1', (string) $result->url);
	}

	public function testLt(): void
	{
		$result = $this->filter->lt('id', '1');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?id=lt.1', (string) $result->url);
	}

	public function testLte(): void
	{
		$result = $this->filter->lte('id', '1');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?id=lte.1', (string) $result->url);
	}

	public function testLike(): void
	{
		$result = $this->filter->like('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=like.Algeria', (string) $result->url);
	}

	public function testiLike(): void
	{
		$result = $this->filter->ilike('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=ilike.Algeria', (string) $result->url);
	}

	public function testIs(): void
	{
		$result = $this->filter->is('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=is.Algeria', (string) $result->url);
	}

	public function testIn(): void
	{
		$result = $this->filter->in('Algeria', ['countries', 'id']);
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?Algeria=in.%28countries%2Cid%29', (string) $result->url);
	}

	public function testContains(): void
	{
		$result = $this->filter->contains('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=cs.Algeria', (string) $result->url);
	}

	public function testContainedBy(): void
	{
		$result = $this->filter->containedBy('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=cd.Algeria', (string) $result->url);
	}

	public function testRangeGt(): void
	{
		$result = $this->filter->rangeGt('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=sr.Algeria', (string) $result->url);
	}

	public function testRangeGte(): void
	{
		$result = $this->filter->rangeGte('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=nxl.Algeria', (string) $result->url);
	}

	public function testRangeLt(): void
	{
		$result = $this->filter->rangeLt('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=sl.Algeria', (string) $result->url);
	}

	public function testRangeLte(): void
	{
		$result = $this->filter->rangeLte('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=nxr.Algeria', (string) $result->url);
	}

	public function testRangeAdjacent(): void
	{
		$result = $this->filter->rangeAdjacent('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=adj.Algeria', (string) $result->url);
	}

	public function testOverlaps(): void
	{
		$result = $this->filter->overlaps('name', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=ov.Algeria', (string) $result->url);
	}

	public function testMatch(): void
	{
		$result = $this->filter->match(['name'=>'Algeria']);
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=eq.Algeria', (string) $result->url);
	}

	public function testNot(): void
	{
		$result = $this->filter->not('name', 'IS', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=not.IS.Algeria', (string) $result->url);
	}

	public function testOr(): void
	{
		$result = $this->filter->or('name');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?0=or&1=%28name%29', (string) $result->url);
	}

	public function testFilter(): void
	{
		$result = $this->filter->filter('name', 'IS', 'Algeria');
		$this->assertEquals('https://some_reference_id.supabase.co/rest/v1/?name=IS.Algeria', (string) $result->url);
	}
}
