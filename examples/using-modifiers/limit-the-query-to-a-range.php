<?php

require '../header.php';
$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')->select('name')
                                    ->range(0, 1)
                                    ->execute();
print_r($response);
