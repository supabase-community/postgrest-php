<?php

require '../header.php';
use Supabase\Postgrest\PostgrestClient;

$opts = [];
$client = new PostgrestClient($reference_id, $api_key);
$response = $client->from('users')->select('
                                        name,
                                        teams (
                                            name
                                        )')->execute();
$output = $response;
print_r($output);
