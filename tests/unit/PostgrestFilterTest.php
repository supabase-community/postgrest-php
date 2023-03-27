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
        $query = $reflection->getProperty('query');
        $params = $reflection->getProperties();

        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        assertEquals('name=eq.Algeria',$params[6]->getValue($result->{'url'}));

        print_r($url->getValue($result->{'url'}));
        echo "\n\n";
        print_r($query->getValue($result->{'url'}));
        echo "\n\n";
        print $params[6]->getValue($result->{'url'});
    
        ob_flush();
    }

    public function testNeq(): void
    {
        $result = $this->filter->neq('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
        assertEquals('eq.Algeria', $result->{'url'}->{'query'});
        ob_flush();
    }

    public function testGt(): void
    {
        $result = $this->filter->gt('id', '1');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
        print_r($result);
        ob_flush();
    }

    public function testGte(): void
    {
        $result = $this->filter->gte('id', '1');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
    }

    public function testLt(): void
    {
        $result = $this->filter->lt('id', '1');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
        print_r($result);
        ob_flush();
    }

    public function testLte(): void
    {
        $result = $this->filter->lte('id', '1');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
    }

    public function testLike(): void
    {
        $result = $this->filter->like('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
        print_r($result);
        ob_flush();
    }

    public function testiLike(): void
    {
        $result = $this->filter->ilike('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
    }

    public function testIs(): void
    {
        $result = $this->filter->is('name', 'algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
    }

    public function testIn(): void
    {
        $result = $this->filter->in('Algeria', ['countries', 'id']);
        print_r($result);
        ob_flush();
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
    }

    public function testContains(): void
    {
        $result = $this->filter->contains('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
    }

    public function testContainedBy(): void
    {
        $result = $this->filter->containedBy('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('host');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $property->getValue($result->{'url'}));
    }

    public function testRangeGt(): void
    {
        $result = $this->filter->rangeGt('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $query = $reflection->getProperty('query');
        //$params = $reflection->getProperty('parameters');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        //assertEquals('',$query->getValue($result->{'url'}));
        print_r($url->getValue($result->{'url'}));
        echo "\n\n";
        print_r($query->getValue($result->{'url'}));
        //($params->getValue($result->{'url'}));
        ob_flush();
    }

    public function testRangeGte(): void
    {
        $result = $this->filter->rangeGte('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $query = $reflection->getProperty('query');
        //$params = $reflection->getProperty('parameters');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        //assertEquals('',$query->getValue($result->{'url'}));
    }

    public function testRangeLt(): void
    {
        $result = $this->filter->rangeLt('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $query = $reflection->getProperty('query');
        //$params = $reflection->getProperty('parameters');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        //assertEquals('',$query->getValue($result->{'url'}));
    }

    public function testRangeLte(): void
    {
        $result = $this->filter->rangeLte('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $query = $reflection->getProperty('query');
        //$params = $reflection->getProperty('parameters');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        //assertEquals('',$query->getValue($result->{'url'}));
        //print_r($params->getValue($result->{'url'}));
    }

    public function testRangeAdjacent(): void
    {
        $result = $this->filter->rangeAdjacent('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $query = $reflection->getProperty('query');
        //$params = $reflection->getProperty('parameters');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        //assertEquals('',$query->getValue($result->{'url'}));
        //print_r($params->getValue($result->{'url'}));
    }

    public function testOverlaps(): void
    {
        $result = $this->filter->overlaps('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $query = $reflection->getProperty('query');
        //$params = $reflection->getProperty('parameters');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        //assertEquals('',$query->getValue($result->{'url'}));
        //print_r($params->getValue($result->{'url'}));
        print_r($query->getValue($result->{'url'}));
        ob_flush();
    }

    public function testMatch(): void
    {
        $result = $this->filter->match('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $url = $reflection->getProperty('host');
        $query = $reflection->getProperty('query');
        //$params = $reflection->getProperty('parameters');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        assertEquals('gpdefvsxamnscceccczu.supabase.co', $url->getValue($result->{'url'}));
        //assertEquals('',$query->getValue($result->{'url'}));
        //print_r($params->getValue($result->{'url'}));
        print_r($query->getValue($result->{'url'}));
        ob_flush();
    }
}
