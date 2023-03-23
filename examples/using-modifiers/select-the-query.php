<?php

require '../header.php';
$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')->upsert(['id'=> 1, 'name'=> 'Algeria'])
                                    ->select()
                                    ->execute();
print_r($response);
