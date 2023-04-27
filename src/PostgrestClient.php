<?php

namespace Supabase\Postgrest;

use Spatie\Url\Url;
use Supabase\Postgrest\Util\Constants;

class PostgrestClient
{
	public $url;
	private $headers;
	private $schema;
	private $reference_id;
	private $api_key;
	private $path;
	private $fetch;
	private $domain;

	public function __construct($reference_id, $api_key, $opts = [], $domain = 'supabase.co', $scheme = 'https', $path = '/rest/v1/')
	{
		$this->url = Url::fromString("{$scheme}://{$reference_id}.{$domain}");
		$headers = ['Authorization' => "Bearer {$api_key}", 'apikey'=>$api_key];
		$this->headers = array_merge(Constants::getDefaultHeaders(), $headers);

		$this->schema = (isset($opts) && isset($opts['schema'])) && $opts['schema'];
		$this->fetch = isset($opts) && isset($opts->fetch) && $opts->fetch;
		$this->reference_id = $reference_id;
		$this->api_key = $api_key;
		$this->domain = $domain;
		$this->path = $path;
	}

	public function from($relation)
	{
		$url = $this->url->withPath($this->path.$relation);

		return new PostgrestQuery($url, [
			'headers' => $this->headers,
			'schema'  => $this->schema,
			'fetch'   => $this->fetch,
		]);
	}

	public function rpc($fn, $args = [], $opts = [])
	{
		$url = $this->url->withPath($this->path.'rpc/'.$fn);

		if (isset($opts['head']) && $opts['head']) {
			$method = 'HEAD';
			foreach ($args as $name => $value) {
				$url->withQueryParameters([$name => strvar($value)]);
			}
		} else {
			$method = 'POST';
			$body = $args;
		}

		if (isset($opts['count']) && $opts['count']) {
			$this->headers['Prefer'] = 'count='.$opts['count'];
		}

		return new PostgrestFilter($url, [
			'headers'    => $this->headers,
			'schema'     => $this->schema,
			'fetch'      => $this->fetch,
			'method'     => $method,
			'body'       => $body,
			'allowEmpty' => false,
		]);
	}
}
