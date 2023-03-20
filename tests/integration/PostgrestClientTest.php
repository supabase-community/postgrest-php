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

    public function testBulkProcessing(): void
    {
        $result = $this->client->rpc('add_one_each', ['arr'=> [1, 2, 3]])->execute();
        $this->assertEquals('200', $result->status);
        $this->assertEquals('OK', $result->statusText);
    }

    /**
     * @dataProvider providerArray
     */
    public function testFetchData($data): void
    {
        $result = $this->client->from('countries')->select()->eq('name', $data)->execute();
        $this->assertEquals('200', $result->status);
        $this->assertEquals('OK', $result->statusText);
        $this->assertCount(3, $result->data[0]);
        $this->assertArrayHasKey('id', $result->data[0]);
        $this->assertArrayHasKey('name', $result->data[0]);
        $this->assertArrayHasKey('created_at', $result->data[0]);
    }

    public static function providerArray(): array
    {
        return[
            ['Algeria'],
            ['Germany'],
            ['Indonesia'],
            ['Armenia'],
        ];
    }
}
