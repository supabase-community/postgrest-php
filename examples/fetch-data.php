<?php

require 'header.php';

$supabaseUrl = "https://{$reference_id}.supabase.co/rest/v1/users?select=first_name";
$service_role = $api_key;

$authHeader = ['Authorization: Bearer '.$service_role, 'apikey: '.$api_key,
    "url: {$reference_id}.supabase.co", ];
$supabaseKey = '';

$request_headers = ['apikey'=>$api_key, 'Authorization'=> 'Bearer '.$service_role];

$client = new PostgrestClient($reference_id, []);
$result = $client->from('users')->select('first_name');
//print_r($result->url->getPath());
//print_r($result->url->getQuery());

$result = $client->from('users')->insert(['first_name'=> 'New', 'last_name' => 'Test'], []);

$queryURL = $result->url->getScheme()."://{$reference_id}.supabase.co/rest/v1".$result->url->getPath().
'?'.$result->url->getQuery();

print_r($queryURL);

$post = new Postgrest(['url' => $queryURL, 'headers' => $request_headers,
    'method'                 => 'POST', 'body'=>  ['first_name'=> 'Denmark tested', 'last_name' => 'Test'], ]);

$result = $post->execute();
$output = json_decode($result->getBody(), true);
print_r($output);
