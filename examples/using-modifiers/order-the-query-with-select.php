<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')->select('id,name')
									->order('id', ['ascending'=> false])
									//->order('name', ['ascending'=> false])
									->execute();
//check when add a second order parameter
//check the abort method
print_r($response);
