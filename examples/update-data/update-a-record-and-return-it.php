<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')->update(['name'=> 'Australia'])->eq('id', 1)->select()->execute();
$output = $response;
print_r($output);
