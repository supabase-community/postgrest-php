<?php

require 'header.php';

$scheme = 'https://';
$domain = '.supabase.co/';
$path = 'rest/v1/';
$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('users')->insert(['first_name'=> 'New', 'last_name' => 'March 8'], [])->execute();
print_r($response->getStatusCode());
print_r($response->getReasonPhrase());
print_r($response->getProtocolVersion());



