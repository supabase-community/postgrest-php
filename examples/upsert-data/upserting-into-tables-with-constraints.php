<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = ['onConflict' => 'handle'];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('users_test')->upsert(['id'=> 42, 'handle'=> 'saoirse',
	'display_name'                                   => 'Saoirse', ], $opts)->select()->execute();
$output = $response;
print_r($output);
