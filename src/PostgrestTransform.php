<?php

namespace Supabase\Postgrest;

class PostgrestTransform extends Postgrest
{
	public function select($columns = '*')
	{
		$quoted = false;
		$cleanedColumns = str_split($columns ?? '*');
		$cleanedColumns = array_map(function ($c) use (&$quoted) {
			if (preg_match('/\s/', $c) && ! $quoted) {
				return '';
			}
			if ($c === '"') {
				$quoted = ! $quoted;
			}

			return $c;
		}, $cleanedColumns);
		$cleanedColumns = implode('', $cleanedColumns);
		$this->url = $this->url->withQueryParameters(['select' => $cleanedColumns]);
		if (isset($this->headers['Prefer'])) {
			$this->headers['Prefer'] .= ',';
		}
		if (! isset($this->headers['Prefer'])) {
			$this->headers = ['Prefer' => ''];
		}
		$this->headers['Prefer'] .= 'return=representation';

		return $this;
	}

	public function order($column, $opts = ['ascending' => true])
	{
		$key = isset($opts['foreignTable']) ? $opts['foreignTable'].'.order' : 'order';

		$existingOrder = $this->url->getQueryParameter($key);
		$this->url = $this->url->withQueryParameters([$key => $existingOrder ? $existingOrder.',' : ''.$column.'.'.($opts['ascending'] ? 'asc' : 'desc').(isset($opts['nullsFirst']) && $opts['nullsFirst'] ? '.nullsfirst' : '.nullslast')]);

		return $this;
	}

	public function limit($count, $opts = [])
	{
		$key = isset($opts['foreignTable']) ? $opts['foreignTable'].'.limit' : 'limit';
		$this->url = $this->url->withQueryParameters([$key => strval($count)]);

		return $this;
	}

	public function range($from, $to, $opts = [])
	{
		$keyOffset = isset($opts['foreignTable']) ? $opts['foreignTable'].'.offset' : 'offset';
		$keyLimit = isset($opts['foreignTable']) ? $opts['foreignTable'].'.limit' : 'limit';
		$this->url = $this->url->withQueryParameters([$keyOffset => strval($from)]);
		$this->url = $this->url->withQueryParameters([$keyLimit => strval($to - $from + 1)]);

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

		$cleanedOptions = join('|', array_filter($options, fn ($v) => ! is_null($v)));
		$forMediaType = $this->headers['Accepts'];
		$format = $opts->format || 'text';
		$this->headers['Accepts'] = 'application/vnd.pgrst.plan+'.$format.'; for="'.$forMediaType.'"; options='.$options.';';

		return $this;
	}

	public function rollback()
	{
		if (strlen(trim($this->headers['Prefer'] ?? '')) > 0) {
			$this->headers['Prefer'] .= ',tx=rollback';
		} else {
			$this->headers['Prefer'] = 'tx=rollback';
		}

		return $this;
	}

	public function returns()
	{
		return $this;
	}
}
