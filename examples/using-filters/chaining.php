<?php

require '../header.php';
$scheme = 'https://';
$domain = '.supabase.co/';
$path = 'rest/v1/';
$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('cities')->select('name, country_id')
                                    ->gte('population', 1000)
                                    ->lt('population', 10000)
                                    ->execute();
$output = $response;
//print_r($output);
