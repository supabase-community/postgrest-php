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
        $this->client = new PostgrestClient($api_key, $reference_id);
    }

    public function testFrom(): void
    {
        $result = $this->client->from('users');
        assertSame('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/users', (string)$result->url);
    }

    public function testCanRPC(): void
    {
        $result = $this->client->rpc('add_one_each', ['arr'=> [1, 2, 3]], ['opts'=>'head']);
        $this->assertSame('https://gpdefvsxamnscceccczu.supabase.co/rest/v1/rpc/add_one', (string)$result->url);
    }
}
