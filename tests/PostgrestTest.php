<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class PostgrestTest extends TestCase {
    public function testCanBeCreatedFromValidUrl(): void {
        $this->assertInstanceOf(
            Postgrest::class,
            new Postgrest(['url' => 'http://localhost:3000'])
        );
    }


}