<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('cities')->select('name, country_id')->eq('name', 'The Shire')->execute();
$output = $response;
print_r($output);
