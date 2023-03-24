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
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        //assertEquals('eq.Algeria', $result->{'url'}->{'query'});
        ob_flush();
    }

    public function testNeq(): void
    {
        $result = $this->filter->neq('name', 'Algeria');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        //assertEquals('eq.Algeria', $result->{'url'}->{'query'});
        ob_flush();
    }

    public function testGt(): void
    {
        $result = $this->filter->gt('id', '1');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        print_r($result);
        ob_flush();
    }

    public function testGte(): void
    {
        $result = $this->filter->gte('id', '1');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
    }

    public function testLt(): void
    {
        $result = $this->filter->lt('id', '1');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        print_r($result);
        ob_flush();
    }

    public function testLte(): void
    {
        $result = $this->filter->lte('id', '1');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
    }

    public function testLike(): void
    {
        $result = $this->filter->like('name', 'Algeria');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
        print_r($result);
        ob_flush();
    }

    public function testiLike(): void
    {
        $result = $this->filter->ilike('name', 'Algeria');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
    }

    public function testIs(): void
    {
        $result = $this->filter->is('name', 'algeria');
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
    }

    public function testIn(): void
    {
        $result = $this->filter->in('Algeria', ['countries', 'id']);
        print_r($result);
        ob_flush();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
    }

    public function testContains(): void
    {
        $result = $this->filter->contains('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'}->{'query'});
        $property = $reflection->getProperty('name');
        $property->setAccessible(true);
        print_r($property);
        ob_flush();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
    }

    public function testContainedBy(): void
    {
        $result = $this->filter->containedBy('name', 'Algeria');
        $reflection = new ReflectionClass($result->{'url'});
        $property = $reflection->getProperty('query');
        $property->setAccessible(true);
        print_r($property->getValue($result->{'url'}));
        ob_flush();
        assertEquals($this->filter->path, $result->{'path'});
        assertEquals($this->filter->domain, $result->{'domain'});
        assertEquals($this->filter->scheme, $result->{'scheme'});
    }
}
