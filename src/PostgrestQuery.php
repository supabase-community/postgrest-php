<?php

use Spatie\Url\Url;

class PostgrestQuery
{
    private $reference_id;
    private $api_key;
    public $url;
    private $headers;
    private $schema;
    private $fetch;

    public function __construct($url, $reference_id, $api_key, $opts = [], $domain = '.supabase.co', $scheme = 'https://', $path = '/rest/v1/')
    {
        $this->url = Url::fromString($scheme.$reference_id.$domain.$path);
        $this->headers = isset($opts['headers']) ? $opts['headers'] : [];
        $this->schema = isset($opts['schema']) ? $opts['schema'] : '';
        $this->shouldThrowOnError = isset($opts['shouldThrowOnError']) && $opts['shouldThrowOnError'];
        $this->signal = isset($opts['signal']) && $opts['signal'];
        $this->allowEmpty = isset($opts['allowEmpty']) && $opts['allowEmpty'];
        $this->fetch = isset($opts) && isset($opts->fetch) && $opts->fetch;
        $this->body = isset($opts['body']) ? $opts['body'] : [];
        $this->reference_id = $reference_id;
        $this->api_key = $api_key;
        $this->domain = $domain;
        $this->path = $path;
    }

    public function select($columns = '*', $opts = [])
    {
        $method = isset($opts['head']) ? 'HEAD' : 'GET';
        $quoted = false;

        $cleanedColumns = join('', array_map(function ($c) {
            if (preg_match('/\s/', $c)) {
                return '';
            }
            if ($c === '"') {
                $quoted = !$quoted;
            }

            return $c;
        }, str_split($columns)));

        $this->url = $this->url->withQueryParameters(['select' => $cleanedColumns]);
        print_r((string) $this->url);
        if (isset($opts['count'])) {
            $this->headers['Prefer'] = 'count='.$opts['count'];
        }
        print_r((string) $this->url);

        return new PostgrestFilter($this->reference_id, $this->api_key, [
            'url'        => $this->url,
            'headers'    => $this->headers,
            'schema'     => $this->schema,
            'fetch'      => $this->fetch,
            'method'     => $method,
            'allowEmpty' => false,
        ]);
    }

    public function insert($values, $opts = [])
    {
        $method = 'POST';
        $body = $values;
        $prefersHeaders = [];

        if (isset($opts['count'])) {
            array_push($prefersHeaders, 'count='.$opts['count']);
        }

        if (isset($this->headers['Prefer'])) {
            array_unshift($prefersHeaders, $this->headers['Prefer']);
        }

        $this->headers['Prefer'] = join(',', $prefersHeaders);

        if (is_array($values)) {
            $columns = array_reduce($values, function ($acc, $x) {
                if (is_array($x)) {
                    return array_merge($acc, array_keys($x));
                }
            }, []);

            if (empty($columns)) {
                $columns = array_keys($values);
            }

            if (count($columns) > 0) {
                $uniqueColumns = array_map(fn ($v) => strval($v), array_unique($columns));
                $this->url = $this->url->withQueryParameters(['columns' => join(',', $uniqueColumns)]);
            }
        }

        return new PostgrestFilter($this->reference_id, $this->api_key, [
            'url'        => $this->url,
            'headers'    => $this->headers,
            'schema'     => $this->schema,
            'fetch'      => $this->fetch,
            'method'     => $method,
            'body'       => $body,
            'allowEmpty' => false,
        ]);
    }

    public function upsert($values, $opts = [])
    {
        $method = 'POST';
        $ignoreDuplicates = isset($opts['ignoreDuplicates']) && isset($opts['ignoreDuplicates']) ? true : false; // or false depending on your requirements
        $prefersHeaders = ['resolution='.($ignoreDuplicates ? 'ignore' : 'merge').'-duplicates'];

        if (isset($opts['onConflict'])) {
            $this->url = $this->url->withQueryParameters(['on_conflict'=>$opts['onConflict']]);
        }

        $body = $values;

        if (isset($opts['count'])) {
            array_push($prefersHeaders, 'count='.$opts['count']);
        }

        if (isset($this->headers['Prefer'])) {
            array_unshift($prefersHeaders, $this->headers['Prefer']);
        }
        $this->headers['Prefer'] = join(',', $prefersHeaders);

        return new PostgrestFilter($this->reference_id, $this->api_key, [
            'url'        => $this->url,
            'headers'    => $this->headers,
            'schema'     => $this->schema,
            'fetch'      => $this->fetch,
            'method'     => $method,
            'body'       => $body,
            'allowEmpty' => false,
        ], $this->headers);
    }

    public function update($values, $opts = [])
    {
        $method = 'PATCH';
        $body = $values;
        $prefersHeaders = [];

        if (isset($opts['count'])) {
            array_push($prefersHeaders, 'count='.$opts['count']);
        }

        if (isset($this->headers['Prefer'])) {
            array_unshift($prefersHeaders, $this->headers['Prefer']);
        }

        $this->headers['Prefer'] = join(',', $prefersHeaders);

        return new PostgrestFilter($this->reference_id, $this->api_key, [
            'url'        => $this->url,
            'headers'    => $this->headers,
            'schema'     => $this->schema,
            'fetch'      => $this->fetch,
            'method'     => $method,
            'body'       => $body,
            'allowEmpty' => false,
        ]);
    }

    public function delete($opts = [])
    {
        $method = 'DELETE';
        $prefersHeaders = [];

        if (isset($opts['count'])) {
            array_push($prefersHeaders, 'count='.$opts['count']);
        }

        if (isset($this->headers['Prefer'])) {
            array_unshift($prefersHeaders, $this->headers['Prefer']);
        }

        $this->headers['Prefer'] = join(',', $prefersHeaders);

        return new PostgrestFilter($this->reference_id, $this->api_key, [
            'url'        => $this->url,
            'headers'    => $this->headers,
            'schema'     => $this->schema,
            'fetch'      => $this->fetch,
            'method'     => $method,
            'allowEmpty' => false,
        ]);
    }
}
