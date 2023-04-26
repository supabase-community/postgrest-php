<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('users')->update([
	'address'=> [
		'street'  => 'Melrose Place',
		'postcode'=> 90210,
	],
])
  ->eq('address->postcode', 90210)
  ->select()->execute();
$output = $response;
print_r($output);
