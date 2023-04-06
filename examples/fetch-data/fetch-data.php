<?php

require __DIR__.'/../header.php';
$client = new PostgrestClient($reference_id, $api_key);
$response = $client->from('countries')->select()->execute();
print_r($response);