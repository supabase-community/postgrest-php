<?php

require '../header.php';

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')->update(['name'=> 'Australia'])->eq('id', 1)->execute();
$output = $response;
print_r($output);
