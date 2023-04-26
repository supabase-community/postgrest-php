<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('cities')->select('name, country_id')
									->gte('population', 1000)
									->lt('population', 10000)
									->execute();
$output = $response;
//Solve error when two arrays keys are the same, just take the last
