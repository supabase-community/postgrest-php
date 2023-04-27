<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('users')->insert(['first_name'=> 'no', 'last_name' => 'bulk'])->select()->execute();
$output = $response;
print_r($output);
