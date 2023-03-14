<?php

require '../header.php';
$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('users')->select()
                                    ->eq('address->postcode', 90210)
                                    ->execute();
print_r($response);
