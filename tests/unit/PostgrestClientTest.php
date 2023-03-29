<?php

declare(strict_types=1);
require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;

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
        $scheme = 'https://';
        $domain = '.supabase.co/';
        $path = 'rest/v1/';
        $opts = [];
        $this->client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
    }

    public function testCanBeCreatedFromValidUrl(): void
    {
        $this->assertInstanceOf(
            PostgrestClient::class,
            new PostgrestClient('http://localhost:3000', [])
        );
    }

    public function testCanSelectTable(): void
    {
        $client = new PostgrestClient('http://localhost:3000', []);

        $this->assertSame($client->from('users')->select()->url->__toString(), 'http://localhost:3000');
    }

    public function testCanSelectColumns(): void
    {
        $client = new PostgrestClient('http://localhost:3000', []);

        $this->assertSame($client->from('users')->select('id, name')->url->__toString(), 'http://localhost:3000/users?select=id%2Cname');
    }

    public function testCanRPC(): void
    {
        $client = new PostgrestClient('http://localhost:3000', []);

        $this->assertSame($client->rpc('add_one')->select()->url->__toString(), 'http://localhost:3000/rpc/add_one');
    }

    public function testCanExecute(): void
    {
        $client = new PostgrestClient('http://localhost:3000', []);

        $this->assertSame($client->from('users')->select()->execute()->status, 200);
    }
}
