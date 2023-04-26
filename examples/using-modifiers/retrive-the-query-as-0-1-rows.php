<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')
									->select()
									->eq('name', 'Singapore')
									->maybeSingle()
									->execute();
print_r($response);
