<?php
require 'header.php';

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('cities')
                        ->select('name, countries(*)')
                        ->eq('countries.name', 'Estonia')->execute();
$output = $response;
print_r($output);
