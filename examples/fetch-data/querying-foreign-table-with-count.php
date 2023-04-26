<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key);
$response = $client->from('countries')->select('*, cities(count)', ['count'=> 'exact',  'head'=> true])->execute();
print_r($response);
