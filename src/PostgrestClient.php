<?php

use Spatie\Url\Url;
use Supabase\Util\Constants;

class PostgrestClient
{
    private $method;
    public $url;
    private $headers;
    private $body;
    private $schema;
    private $shouldThrowOnError;
    private $signal;
    private $allowEmpty;
    private $reference_id;
    private $scheme;
    private $domain;
    private $api_key;
    private $path;
    private $fetch;

    public function __construct($reference_id, $api_key, $opts = [], $domain = '', $schema = '', $path = '')
    {

        $this->url = $reference_id ?  Url::fromString($schema.$reference_id.$domain) : Url::fromString($schema.$reference_id.$domain);
        $headers = ['Authorization' => "Bearer {$api_key}", 'apikey'=>$api_key];
        $this->headers = array_merge(Constants::getDefaultHeaders(), $headers);
        $this->schema = $schema;
        $this->fetch = isset($opts) && isset($opts->fetch) && $opts->fetch;
        $this->reference_id = $reference_id;
        $this->api_key = $api_key;
        $this->domain = $domain;
        $this->path = $path;
    }

    public function from($relation)
    {
        $url = $this->url->withPath($this->path.$relation);

        return new PostgrestQuery($url, $this->reference_id, $this->api_key, [
            'headers' => $this->headers,
            'schema'  => $this->schema,
            'fetch'   => $this->fetch,
        ]);
    }

    public function rpc($fn, $args = [], $opts = [])
    {
        $url = $this->url->withPath('/rpc/'.$fn);

        if (isset($opts->head) && $opts->head) {
            $method = 'HEAD';
            foreach ($args as $name => $value) {
                $url->withQueryParameters([$name => strvar($value)]);
            }
        } else {
            $method = 'POST';
            $body = $args;
        }

        if (isset($opts->count) && $opts->count) {
            $this->headers['Prefer'] = 'count='.$opts->count;
        }

        return new PostgrestFilter($url, $this->reference_id, [
            'url'        => $url,
            'headers'    => $this->headers,
            'schema'     => $this->schema,
            'fetch'      => $this->fetch,
            'method'     => $method,
            'body'       => $body,
            'allowEmpty' => false,
        ]);
    }
}
