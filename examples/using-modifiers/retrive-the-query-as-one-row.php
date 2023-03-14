<?php

require '../header.php';
$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')
                                    ->select('name')
                                    ->limit(1)
                                    ->single()
                                    ->execute();
print_r($response);
