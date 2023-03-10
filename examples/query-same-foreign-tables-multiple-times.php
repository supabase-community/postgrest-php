<?php
require 'header.php';

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('messages')->select('
                                        content,
                                        from:sender_id(name),
                                        to:receiver_id(name)
                                        ')->execute();
$output = $response;
print_r($output);
