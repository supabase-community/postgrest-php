<?php
use Spatie\Url\Url;
use Supabase\Util\Constants;
class PostgrestClient
{
    public function __construct($reference_id, $api_key, $opts = [])
    {
        $this->url = Url::fromString("https://{$reference_id}.supabase.co/rest/v1");
        $headers = ['Authorization' => "Bearer {$api_key}", 'apikey'=>$api_key];
        $this->headers = array_merge(Constants::getDefaultHeaders(), $headers);
        $this->schema = isset($opts) && isset($opts->schema) && $opts->schema;
        $this->fetch = isset($opts) && isset($opts->fetch) && $opts->fetch;
        $this->refrence_id = $reference_id;
        $this->api_key = $api_key;
    }

    public function from($relation)
    {
        $url = $this->url->withPath('rest/v1/'.$relation);

        return new PostgrestQuery($url,  $this->refrence_id,  $this->api_key, [
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

        return new PostgrestFilter($url,  $this->refrence_id,[
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
