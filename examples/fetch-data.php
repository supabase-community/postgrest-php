<?php

require 'header.php';

$supabaseUrl = "https://{$reference_id}.supabase.co/rest/v1/users?select=first_name";
$service_role = $api_key;

$authHeader = ['Authorization: Bearer '.$service_role, 'apikey: '.$api_key,
    "url: {$reference_id}.supabase.co", ];
$supabaseKey = '';
$request_headers = [];
$request_headers[] = 'Authorization: Bearer '.$service_role;
$request_headers[] = 'apikey: '.$api_key;

$client = new PostgrestClient($reference_id, []);
$result = $client->from('users')->select('first_name');
print_r($result->url->getPath());
print_r($result->url->getQuery());

$queryURL = $result->url->getScheme()."://{$reference_id}.supabase.co/rest/v1/".$result->url->getPath().
'?'.$result->url->getQuery();

$post = new Postgrest(['url' => $queryURL, 'headers' => $request_headers,
    'method'                 => 'GET', ]);

print_r($post->execute());
