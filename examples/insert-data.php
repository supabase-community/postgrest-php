<?php

require 'header.php';

$supabaseUrl = "https://{$reference_id}.supabase.co/rest/v1/users?select=first_name";
$service_role = $api_key;

$authHeader = ['Authorization: Bearer '.$service_role, 'apikey: '.$api_key,
    "url: {$reference_id}.supabase.co", ];
$supabaseKey = '';

$request_headers = ['apikey'=>$api_key, 'Authorization'=> 'Bearer '.$service_role];

$client = new PostgrestClient($reference_id, $api_key, []);
$result = $client->from('users')->select('first_name');
$result = $client->from('users')->insert(['first_name'=> 'New', 'last_name' => 'Test 2'], [])->execute();
print_r($result);
