<?php

namespace Supabase\Postgrest;

use Supabase\Postgrest\Util\PostgrestError;
use Supabase\Postgrest\Util\Request;

class Postgrest
{
	private $method;
	public $url;
	public $headers;
	private $body;
	private $schema;
	private $fetch;
	private $shouldThrowOnError;
	private $signal;
	public $allowEmpty;

	public function __construct($url, $opts = [])
	{
		$this->method = (isset($opts['method']) && in_array($opts['method'], ['GET', 'HEAD', 'POST', 'PATCH', 'PUT', 'DELETE'])) ? $opts['method'] : null;
		$this->url = $url;
		$this->headers = isset($opts['headers']) ? $opts['headers'] : [];
		$this->schema = isset($opts['schema']) ? $opts['schema'] : '';
		$this->shouldThrowOnError = isset($opts['shouldThrowOnError']) && $opts['shouldThrowOnError'];
		$this->signal = isset($opts['signal']) && $opts['signal'];
		$this->allowEmpty = isset($opts['allowEmpty']) && $opts['allowEmpty'];
		$this->fetch = isset($opts) && isset($opts->fetch) && $opts->fetch;
		$this->body = isset($opts['body']) ? $opts['body'] : [];
	}

	public function execute()
	{
		if ($this->schema) {
			if ($this->method == 'GET' || $this->method == 'HEAD') {
				$this->headers = array_merge($this->headers, ['Accept-Profile' => $this->schema]);
			} else {
				$this->headers = array_merge($this->headers, ['Content-Profile' => $this->schema]);
				//$this->headers[] = 'Content-Profile: '.$this->schema;
			}
		}

		if ($this->method != 'GET' || $this->method != 'HEAD') {
			$this->headers = array_merge($this->headers, ['content-type' =>'application/json']);
		}
		$data = null;
		$count = null;
		$error = null;

		try {
			$response = Request::request($this->method, $this->url->__toString(), $this->headers, json_encode($this->body));
			$status = $response->getStatusCode();
			$statusText = $response->getReasonPhrase();

			if ($this->method != 'HEAD') {
				$body = $response->getBody();
				if ($body === '') {
					// Prefer: return=minimal
				} elseif (isset($this->headers['Accept']) && $this->headers['Accept'] === 'text/csv') {
					$data = $body->getContents();
				} elseif (
					isset($this->headers['Accept']) &&
					strpos($this->headers['Accept'], 'application/vnd.pgrst.plan+text') !== false
				) {
					$data = $body->getContents();
				} else {
					$data = json_decode($body, true);
				}
			}

			$countHeader = isset($this->headers['Prefer']) ? preg_match('/count=(exact|planned|estimated)/', $this->headers['Prefer'], $matches) : null;
			$contentRange = $response->getHeader('content-range')[0];
			if ($countHeader && $contentRange) {
				$ranges = explode('/', $contentRange);
				if (count($ranges) > 1) {
					$count = $ranges[1];
				}
			}

			$postgrestResponse = new PostgrestResponse($data, $error, $count, $status, $statusText);

			return $postgrestResponse;

			return $response;
		} catch (\Exception $e) {
			if (PostgrestError::isPostgrestError($e)) {
				if ($e->response) {
					$body = $e->response->getBody();
					$error = json_decode($body, true);

					// Workaround for https://github.com/supabase/postgrest-js/issues/295
					if (is_array($error) && $e->response->getStatusCode() === 404) {
						$data = [];
						$error = null;
						$status = 200;
						$statusText = 'OK';
					}
				} else {
					// Workaround for https://github.com/supabase/postgrest-js/issues/295
					if ($e->response->getStatusCode() === 404 && $body === '') {
						$status = 204;
						$statusText = 'No Content';
					} else {
						$error = [
							'message' => $body,
						];
					}
				}
				//$error = $e->response->getStatusCode();

				if ($error && $this->allowEmpty && strpos($error['details'], 'Results contain 0 rows') !== false) {
					$error = null;
					$status = 200;
					$statusText = 'OK';
				}

				if ($error && $this->shouldThrowOnError) {
					throw $e;
				}

				$postgrestResponse = new PostgrestResponse($error, $data, $count, $status, $statusText);

				return $postgrestResponse;

				return new PostgrestResponse(null, [
					'message' => $e->getMessage(),
					'details' => isset($e->details) ? $e->details : '',
					'hint'    => isset($e->hint) ? $e->hint : '',
					'code'    => is_null($e) ? $e->getCode() : null,
				], null, is_null($e) ? $e->response->getStatusCode() : null);
			}

			throw $e;
		}
	}
}

class PostgrestResponse
{
	public mixed $data;
	public mixed $error;
	public int $count;
	public int $status;
	public string $statusText;

	public function __construct($data = '', $error = null, $count = 0, $status = 0, $statusText = '')
	{
		$this->data = $data;
		$this->error = $error;
		$this->count = $count ?? 0;
		$this->status = $status ?? 0;
		$this->statusText = $statusText ?? '';
	}
}
