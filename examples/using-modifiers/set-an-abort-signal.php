<?php

require '../header.php';
$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('very_big_table')->select()->abortSignal('')
                                    ->execute();
print_r($response);
