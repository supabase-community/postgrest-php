<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')->select('name,
                                                cities (
                                                name
                                                )')
									->limit(1, ['foreignTable'=> 'cities'])
									->execute();
print_r($response);
