<?php

declare(strict_types=1);
require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

final class PostgrestClientTest extends TestCase
{
    private $client;

    public function setup(): void
    {
        parent::setUp();
        \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__, '/../../.env.test');
        $dotenv->load();
        $api_key = getenv('API_KEY');
        $reference_id = getenv('REFERENCE_ID');
        $this->client = new PostgrestClient($reference_id, $api_key, $opts = [], $domain = '.supabase.co', $scheme = 'https://', $path = '/rest/v1/');
    }

    public function testFrom(): void
    {
        $result = $this->client->from('users');
        assertSame('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/users', $result->url->__toString());
    }

    public function testCanRPC(): void
    {
        $result = $this->client->rpc('add_one');
        $this->assertSame('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/rpc/add_one', $result->url->__toString());
    }
}
