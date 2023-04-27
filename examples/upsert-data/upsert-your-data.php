<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = ['ignoreDuplicates' => true, 'onConflict' => 'id'];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')->upsert(['id'=>1, 'name'=> 'Albania'], $opts)->select()->execute();
$output = $response;
print_r($output);
