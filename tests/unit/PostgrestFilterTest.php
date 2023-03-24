<?php

declare(strict_types=1);
require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertCount;

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
        assertCount(13, $result);
        var_dump($result);
        ob_flush();
    }
}
