<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PostgrestTransformTest extends TestCase
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
                $this->query = new \Supabase\Postgrest\PostgrestQuery($reference_id, $api_key, $opts = []);
        }


}
