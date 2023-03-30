<?php

declare(strict_types=1);
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

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
        $url = $this->url;
        $this->query = new PostgrestQuery($url, $reference_id, $api_key, $opts = []);
        
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
        print_r($result->url->__toString());
        print_r($result);
        ob_flush();
        assertEquals('gpdefvsxamnscceccczu',$result->url->__toString());
    }

    public function testUpsert()
    {
        $result = $this->query->upsert('countries');
        print_r($result->url->__toString());
        print_r($result);
        ob_flush();
        assertEquals('',$result);
    }

    public function testUpdate()
    {
        $result = $this->query->update('countries');
        print_r($result->url->__toString());
        print_r($result);
        ob_flush();
        assertEquals('gpdefvsxamnscceccczu',$result->url->__toString());
    }

    public function testDelete()
    {
        $result = $this->query->delete('countries');
        print_r($result->url->__toString());
        print_r($result);
        ob_flush();
        assertEquals('gpdefvsxamnscceccczu',$result->url->__tostring());
    }
}
