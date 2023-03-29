<?php

declare(strict_types=1);
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

require __DIR__.'/../../src/PostgrestFilter.php';

final class PostgrestFilterTest extends TestCase
{
    private $filter;

    public function setup(): void
    {
        parent::setUp();
        \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__, '/../../.env.test');
        $dotenv->load();
        $api_key = getenv('API_KEY');
        $reference_id = getenv('REFERENCE_ID');
        $this->filter = new PostgrestFilter($reference_id, $api_key, $opts = [], '.supabase.co/', 'https://', 'rest/v1/');
    }

    public function testEq(): void
    {
        $result = $this->filter->eq('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=eq.Algeria', $result->url->__toString());
    }

    public function testNeq(): void
    {
        $result = $this->filter->neq('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=neq.Algeria', $result->url->__toString());
    }

    public function testGt(): void
    {
        $result = $this->filter->gt('id', '1');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?id=gt.1', $result->url->__toString());
    }

    public function testGte(): void
    {
        $result = $this->filter->gte('id', '1');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?id=gte.1', $result->url->__toString());
    }

    public function testLt(): void
    {
        $result = $this->filter->lt('id', '1');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?id=lt.1', $result->url->__toString());
    }

    public function testLte(): void
    {
        $result = $this->filter->lte('id', '1');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?id=lte.1', $result->url->__toString());
    }

    public function testLike(): void
    {
        $result = $this->filter->like('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=like.Algeria', $result->url->__toString());
    }

    public function testiLike(): void
    {
        $result = $this->filter->ilike('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=ilike.Algeria', $result->url->__toString());
    }

    public function testIs(): void
    {
        $result = $this->filter->is('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=is.Algeria', $result->url->__toString());
    }

    public function testIn(): void
    {
        $result = $this->filter->in('Algeria', ['countries', 'id']);
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?Algeria=in.%28countries%2Cid%29', $result->url->__toString());
    }

    public function testContains(): void
    {
        $result = $this->filter->contains('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=cs.Algeria', $result->url->__toString());
    }

    public function testContainedBy(): void
    {
        $result = $this->filter->containedBy('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=cd.Algeria', $result->url->__toString());
    }

    public function testRangeGt(): void
    {
        $result = $this->filter->rangeGt('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=sr.Algeria', $result->url->__toString());
    }

    public function testRangeGte(): void
    {
        $result = $this->filter->rangeGte('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=nxl.Algeria', $result->url->__toString());
    }

    public function testRangeLt(): void
    {
        $result = $this->filter->rangeLt('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=sl.Algeria', $result->url->__toString());
    }

    public function testRangeLte(): void
    {
        $result = $this->filter->rangeLte('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=nxr.Algeria', $result->url->__toString());
    }

    public function testRangeAdjacent(): void
    {
        $result = $this->filter->rangeAdjacent('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=adj.Algeria', $result->url->__toString());
    }

    public function testOverlaps(): void
    {
        $result = $this->filter->overlaps('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=ov.Algeria', $result->url->__toString());
    }

    public function testMatch(): void
    {
        $result = $this->filter->match('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co', $result->url->__toString());
    }

    public function testNot(): void
    {
        $result = $this->filter->not('name', 'IS', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=not.IS.Algeria', $result->url->__toString());
    }

    public function testOr(): void
    {
        $result = $this->filter->or('name', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?0=or&1=%28name%29', $result->url->__toString());
    }

    public function testFilter(): void
    {
        $result = $this->filter->filter('name', 'IS', 'Algeria');
        assertEquals('https://gpdefvsxamnscceccczu.supabase.co?name=IS.Algeria', $result->url->__toString());
    }
}
