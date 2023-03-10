<?php
require 'header.php';

$opts = [];
$client = new PostgrestClient($reference_id, $api_key, $opts, $domain, $scheme, $path);
$response = $client->from('users')->select('
                                        name,
                                        teams (
                                            name
                                        )')->execute();
$output = $response;
print_r($output);
