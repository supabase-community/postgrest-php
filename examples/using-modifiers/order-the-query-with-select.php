<?php

require '../header.php';
$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('countries')->select('id,name')
                                    ->order('id', ['ascending'=> false])
                                    ->execute();
//print_r($response);
