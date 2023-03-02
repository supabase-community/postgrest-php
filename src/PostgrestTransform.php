<?php

class PostgrestTransform extends Postgrest
{
    public function select($columns = '*')
    {
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

        $this->url->withQueryParameters(['select' => $cleanedColumns]);

        if (isset($this->headers) && $this->headers['Prefer']) {
            $this->headers['Prefer'] += ',';
        }

        if (!isset($this->headers)) {
            $this->headers = ['Prefer' => ''];
        }

        $this->headers['Prefer'] = $this->headers['Prefer'].'return=representation';

        return $this;
    }

    public function order($column, $opts = ['ascending' => true])
    {
        $key = $opts->foreignTable ? $opts->foreignTable.'.order' : 'order';
        $existingOrder = $this->url->searchParams->get($key);
        $this->url->searchParams->set($key, $existingOrder ? $existingOrder.',' : ''.$column.($opts->ascending ? 'asc' : 'desc').(isset($opts->nullsFirst) && $opts->nullsFirst ? '.nullsfirst' : '.nullslast'));

        return $this;
    }

    public function limit($count, $opts)
    {
        $key = $opts->foreignTable ? $opts->foreignTable.'.limit' : 'limit';
        $this->url->searchParams->set($key, strval($count));

        return $this;
    }

    public function range($from, $to, $opts)
    {
        $keyOffset = $opts->foreignTable ? $opts->foreignTable.'.offset' : 'offset';
        $keyLimit = $opts->foreignTable ? $opts->foreignTable.'.limit' : 'limit';
        $this->url->searchParams->set($keyOffset, strval($from));
        $this->url->searchParams->set($keyLimit, strval($to - $from + 1));

        return $this;
    }

    public function abortSignal($signal)
    {
        $this->signal = $signal;

        return $this;
    }

    public function single()
    {
        $this->headers['Accept'] = 'application/vnd.pgrst.object+json';

        return $this;
    }

    public function maybeSingle()
    {
        $this->headers['Accept'] = 'application/vnd.pgrst.object+json';
        $this->allowEmpty = true;

        return $this;
    }

    public function csv()
    {
        $this->headers['Accept'] = 'text/csv';

        return $this;
    }

    public function geojson()
    {
        $this->headers['Accept'] = 'application/vnd.geo+json';

        return $this;
    }

    public function explain($opts)
    {
        $options = [
            $opts->analyze ? 'analyze' : null,
            $opts->verbose ? 'verbose' : null,
            $opts->settings ? 'settings' : null,
            $opts->buffers ? 'buffers' : null,
            $opts->wal ? 'wal' : null,
        ];

        $cleanedOptions = join('|', array_filter($options, fn ($v) => !is_null($v)));
        $forMediaType = $this->headers['Accepts'];
        $format = $opts->format || 'text';
        $this->headers['Accepts'] = 'application/vnd.pgrst.plan+'.$format.'; for="'.$forMediaType.'"; options='.$options.';';

        return $this;
    }

    public function rollback()
    {
        if (count(trim($_a = $this->headers['Prefer'] ? $_a : '')) > 0) {
            $this->headers['Prefer'] += ',tx=rollback';
        } else {
            $this->headers['Prefer'] = ',tx=rollback';
        }

        return $this;
    }

    public function returns()
    {
        return $this;
    }
}

function cleanQueryArr($q)
{
    if (preg_match('/\s/', $q)) {
        $quotes = true;
    }
}
