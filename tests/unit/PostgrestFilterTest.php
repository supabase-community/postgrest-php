<?php

declare(strict_types=1);
require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;

require __DIR__.'../../src/PostgrestFilter.php';

final class PostgrestTest extends TestCase
{
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
        $filter = new PostgrestFilter($reference_id, $api_key, $opts = [], '.supabase.co/', 'https://', 'rest/v1/');
    }

    /**
     * @dataProvider countryProvider
     */
    public function testEq($data): void
    {
        $result = $this->filter->eq('name', $data)->execute();
        $this->assertEquals('result', $result);
    }
}
