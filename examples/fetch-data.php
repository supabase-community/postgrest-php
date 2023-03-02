<?php
include __DIR__.'/../vendor/autoload.php';

$supabaseUrl = 'https://<reference_id>.supabase.co/rest/v1/users?select=first_name';
$service_role = '<service_role>';
$apiKey = '<anon_key>';

$authHeader = ['Authorization: Bearer ' . $service_role, 'apikey: '.$apiKey,
 'url: <reference_id>.supabase.co'];
$supabaseKey = '';
$request_headers = array();
$request_headers[] = 'Authorization: Bearer ' . $service_role;
$request_headers[] = 'apikey: '.$apiKey;


$client = new PostgrestClient($supabaseUrl, []);
$result = $client->from('users')->select('first_name');
print_r($result->url->getPath());
print_r($result->url->getQuery());

$queryURL = 'https://<reference_id>.supabase.co/rest/v1/'.$result->url->getPath().
'?'.$result->url->getQuery();

$post = new Postgrest(['url' => $queryURL, 'headers' => $request_headers,
 'method' => 'GET']);

print_r($post->execute());