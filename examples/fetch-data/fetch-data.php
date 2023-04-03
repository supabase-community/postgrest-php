<?php

require __DIR__.'/../header.php';
$client = new PostgrestClient($api_key, $reference_id, $opts = [], $domain = '.supabase.co', $scheme = 'https://', $path = '/rest/v1');
$response = $client->from('countries')->select()->execute();
print_r($response);
