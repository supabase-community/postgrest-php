<?php

namespace Supabase\Postgrest;

class PostgrestQuery
{
	public $url;
	private $headers;
	private $schema;
	private $fetch;

	public function __construct($url, $opts = [])
	{
		$this->url = $url;
		$this->headers = (isset($opts) && isset($opts['headers'])) ? $opts['headers'] : [];
		$this->schema = isset($opts) && isset($opts['schema']) && $opts['schema'];
		$this->fetch = isset($opts) && isset($opts['fetch']) && $opts['fetch'];
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
				$quoted = ! $quoted;
			}

			return $c;
		}, str_split($columns)));

		$url = $this->url->withQueryParameters(['select' => $cleanedColumns]);
		if (isset($opts['count'])) {
			$this->headers['Prefer'] = 'count='.$opts['count'];
		}

		return new PostgrestFilter($url, [
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

		return new PostgrestFilter($this->url, [
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

		return new PostgrestFilter($this->url, [
			'headers'    => $this->headers,
			'schema'     => $this->schema,
			'fetch'      => $this->fetch,
			'method'     => $method,
			'body'       => $body,
			'allowEmpty' => false,
		]);
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

		return new PostgrestFilter($this->url, [
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

		return new PostgrestFilter($this->url, [
			'headers'    => $this->headers,
			'schema'     => $this->schema,
			'fetch'      => $this->fetch,
			'method'     => $method,
			'allowEmpty' => false,
		]);
	}
}
