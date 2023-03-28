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
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=eq.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testNeq(): void
    {
        $result = $this->filter->neq('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=neq.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testGt(): void
    {
        $result = $this->filter->gt('id', '1');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('id=gt.1', $params[6]->getValue($result->{'url'}));
    }

    public function testGte(): void
    {
        $result = $this->filter->gte('id', '1');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('id=gte.1', $params[6]->getValue($result->{'url'}));
    }

    public function testLt(): void
    {
        $result = $this->filter->lt('id', '1');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('id=lt.1', $params[6]->getValue($result->{'url'}));
    }

    public function testLte(): void
    {
        $result = $this->filter->lte('id', '1');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('id=lte.1', $params[6]->getValue($result->{'url'}));
    }

    public function testLike(): void
    {
        $result = $this->filter->like('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=Like.Algeria', $params[6]->getValue($result->{'url'}));

        //
    }

    public function testiLike(): void
    {
        $result = $this->filter->ilike('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=ilike.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testIs(): void
    {
        $result = $this->filter->is('name', 'algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=is.algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testIn(): void
    {
        $result = $this->filter->in('Algeria', ['countries', 'id']);
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('Algeria=in.(countries,id)', $params[6]->getValue($result->{'url'}));

        //
    }

    public function testContains(): void
    {
        $result = $this->filter->contains('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=cs.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testContainedBy(): void
    {
        $result = $this->filter->containedBy('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=cd.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testRangeGt(): void
    {
        $result = $this->filter->rangeGt('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=sr.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testRangeGte(): void
    {
        $result = $this->filter->rangeGte('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=nxl.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testRangeLt(): void
    {
        $result = $this->filter->rangeLt('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=sl.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testRangeLte(): void
    {
        $result = $this->filter->rangeLte('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=nxr.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testRangeAdjacent(): void
    {
        $result = $this->filter->rangeAdjacent('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=adj.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testOverlaps(): void
    {
        $result = $this->filter->overlaps('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=ov.Algeria', $params[6]->getValue($result->{'url'}));
    }

    public function testMatch(): void
    {
        $result = $this->filter->match('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $params = $reflection->getProperties();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('', $params[6]->getValue($result->{'url'}));
    }
}
