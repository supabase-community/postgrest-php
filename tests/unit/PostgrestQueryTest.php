<?php

declare(strict_types=1);
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

require __DIR__.'/../../src/PostgrestFilter.php';

final class PostgrestQueryTest extends TestCase
{
    private $query;

    public function setup(): void
    {
        parent::setUp();
        \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__, '/../../.env.test');
        $dotenv->load();
        $api_key = getenv('API_KEY');
        $reference_id = getenv('REFERENCE_ID');
        $url = new PostgrestQuery::$url();
        $this->query = new PostgrestQuery($url, $reference_id, $api_key, $opts = []);
    }

    public function testSelect()
    {
        $result = $this->query->select($columns = '*', $opts = []);
        print_r($result);
        ob_flush();
    }
}
