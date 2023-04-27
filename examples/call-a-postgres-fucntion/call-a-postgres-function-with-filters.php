<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$scheme = 'https://';
$domain = '.supabase.co/';
$path = 'rest/v1/';
$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->rpc('list_stored_countries')->eq('id', 1)->single()->execute();
//$output = json_decode($response->getBody(), true);
$output = $response;
print_r($output);
