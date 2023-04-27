<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$client = new PostgrestClient($reference_id, $api_key);
$response = $client->from('users')->select('id, name, address->city')->execute();
$output = $response;
print_r($output);
