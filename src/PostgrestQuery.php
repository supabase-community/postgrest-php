<?php

class PostgrestQuery
{
    private $reference_id;
    private $api_key;

    public function __construct($url, $reference_id, $api_key, $opts = [])
    {
        $this->url = $url;
        $this->headers = (isset($opts) && isset($opts['headers'])) ? $opts['headers'] : [];
        $this->schema = isset($opts) && isset($opts->schema) && $opts->schema;
        $this->fetch = isset($opts) && isset($opts->fetch) && $opts->fetch;
        $this->refrence_id = $reference_id;
        $this->api_key = $api_key;
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

        if (isset($opts['count'])) {
            $this->headers['Prefer'] = 'count='.$opts['count'];
        }

        return new PostgrestFilter($this->reference_id, $this->api_key, [
            'url'        => $this->url,
            'headers'    => $this->headers,
            'schema'     => $this->schema,
            'fetch'      => $this->fetch,
            'method'     => $method,
            'allowEmpty' => false,
        ]);
    }

    public function insert($values, $opts)
    {
        $method = 'POST';
        $body = $values;
        $prefersHeaders = [];

        if (isset($opts['count'])) {
            array_push($prefersHeaders, 'count='.$opts->count);
        }

        if (isset($this->headers['Prefer'])) {
            array_unshift($prefersHeaders, $this->headers['Prefer']);
        }

        $this->headers['Prefer'] = join(',', $prefersHeaders);

        if (is_array($values)) {

            /*$columns = array_reduce($values, function($acc, $x) {
                print_r($acc, $x);
                return array_merge($acc, array_keys($x));
            }, []);*/
            $columns = array_keys($values);
            //print_r($columns);

            if (count($columns) > 0) {
                //print_r($this->url->__toString());
                $uniqueColumns = array_map(fn ($v) => strval($v), array_unique($columns));
                $this->url = $this->url->withQueryParameters(['columns'=> join(',', $uniqueColumns)]);
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

    public function upsert($values, $opts)
    {
        $method = 'POST';
        $prefersHeaders = ['resolution='.$opts->ignoreDuplicates ? 'ignore' : 'merge'.'-duplicates'];
        if ($opts->onConflict) {
            $this->url = $this->url->withQueryParameters('on_conflict', $opts->onConflict);
        }

        $body = $values;

        if ($opts->count) {
            array_push($prefersHeaders, 'count='.$opts->count);
        }

        if ($this->headers['Prefer']) {
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

    public function update($values, $opts)
    {
        $method = 'PATCH';
        $body = $values;
        $prefersHeaders = [];

        if ($opts->count) {
            array_push($prefersHeaders, 'count='.$opts->count);
        }

        if ($this->headers['Prefer']) {
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

    public function delete($opts)
    {
        $method = 'DELETE';
        $prefersHeaders = [];

        if ($opts->count) {
            array_push($prefersHeaders, 'count='.$opts->count);
        }

        if ($this->headers['Prefer']) {
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
