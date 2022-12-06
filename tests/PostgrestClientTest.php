<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class PostgrestClientTest extends TestCase {
    public function testCanBeCreatedFromValidUrl(): void {
        $this->assertInstanceOf(
            PostgrestClient::class,
            new PostgrestClient('http://localhost:3000', [])
        );
    }

    public function testCanSelectTable(): void {

        $client = new PostgrestClient('http://localhost:3000', []);

        $this->assertSame($client->from('users')->select()->url->__toString(), 'http://localhost:3000/users?select=%2A');
    }

    public function testCanSelectColumns(): void {

        $client = new PostgrestClient('http://localhost:3000', []);

        $this->assertSame($client->from('users')->select('id, name')->url->__toString(), 'http://localhost:3000/users?select=id%2Cname');
    }

    public function testCanRPC(): void {

        $client = new PostgrestClient('http://localhost:3000', []);

        $this->assertSame($client->rpc('add_one')->select()->url->__toString(), 'http://localhost:3000/rpc/add_one');
    }

    public function testCanExecute(): void {

        $client = new PostgrestClient('http://localhost:3000', []);

        $this->assertSame($client->from('users')->select()->execute()->status, 200);
    }
}