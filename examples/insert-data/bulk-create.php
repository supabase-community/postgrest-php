<?php

require '../header.php';

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('users')->insert([['first_name'=> 'with', 'last_name' => 'bulk'], ['first_name'=> 'with', 'last_name' => 'bulk 2']])->select()->execute();
$output = $response;
print_r($output);
