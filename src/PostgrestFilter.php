<?php

namespace Supabase\Postgrest;

$FilterOperators = ['eq', 'neq', 'gt', 'gte', 'lt', 'lte', 'like', 'ilike', 'is', 'in', 'cs', 'cd', 'sl', 'sr', 'nxr', 'nxl', 'adj', 'ov', 'fts', 'plfts', 'phfts', 'wfts'];
class PostgrestFilter extends PostgrestTransform
{
	public function eq($column, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'eq.'.$value]);

		return $this;
	}

	public function neq($column, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'neq.'.$value]);

		return $this;
	}

	public function gt($column, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'gt.'.$value]);

		return $this;
	}

	public function gte($column, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'gte.'.$value]);

		return $this;
	}

	public function lt($column, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'lt.'.$value]);

		return $this;
	}

	public function lte($column, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'lte.'.$value]);

		return $this;
	}

	public function like($column, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'like.'.$value]);

		return $this;
	}

	public function ilike($column, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'ilike.'.$value]);

		return $this;
	}

	public function is($column, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'is.'.$value]);

		return $this;
	}

	public function in($column, $values)
	{
		$cleanedValues = join(',', array_map(function ($s) {
			if (is_string($s) && preg_match('/[,()]/', $s)) {
				return strval('"'.$s.'"');
			} else {
				return strval($s);
			}
		}, $values));

		$this->url = $this->url->withQueryParameters([$column => 'in.('.$cleanedValues.')']);

		return $this;
	}

	public function contains($column, $value)
	{
		if (is_string($value)) {
			$this->url = $this->url->withQueryParameters([$column => 'cs.'.$value]);
		} elseif (is_array($value)) {
			$this->url = $this->url->withQueryParameters([$column => 'cs.('.join(',', $value).')']);
		} else {
			$this->url = $this->url->withQueryParameters([$column => 'cs.('.json_encode($value).')']);
		}

		return $this;
	}

	public function containedBy($column, $value)
	{
		if (is_string($value)) {
			$this->url = $this->url->withQueryParameters([$column => 'cd.'.$value]);
		} elseif (is_array($value)) {
			$this->url = $this->url->withQueryParameters([$column => 'cd.('.join(',', $value).')']);
		} else {
			$this->url = $this->url->withQueryParameters([$column => 'cd.('.json_encode($value).')']);
		}

		return $this;
	}

	public function rangeGt($column, $range)
	{
		$this->url = $this->url->withQueryParameters([$column => 'sr.'.$range]);

		return $this;
	}

	public function rangeGte($column, $range)
	{
		$this->url = $this->url->withQueryParameters([$column => 'nxl.'.$range]);

		return $this;
	}

	public function rangeLt($column, $range)
	{
		$this->url = $this->url->withQueryParameters([$column => 'sl.'.$range]);

		return $this;
	}

	public function rangeLte($column, $range)
	{
		$this->url = $this->url->withQueryParameters([$column => 'nxr.'.$range]);

		return $this;
	}

	public function rangeAdjacent($column, $range)
	{
		$this->url = $this->url->withQueryParameters([$column => 'adj.'.$range]);

		return $this;
	}

	public function overlaps($column, $value)
	{
		if (is_string($value)) {
			$this->url = $this->url->withQueryParameters([$column => 'ov.'.$value]);
		} else {
			$this->url = $this->url->withQueryParameters([$column => 'ov.('.join(',', $value).')']);
		}

		return $this;
	}

	public function textSearch($column, $opts)
	{
		$typePart = '';
		if ($opts->plain) {
			$typePart = 'pl';
		} elseif ($opts->phrase) {
			$typePart = 'ph';
		} elseif ($opts->websearch) {
			$typePart = 'w';
		}

		$configPart = $opts->config ? '('.$opts->config.')' : '';
		$this->url = $this->url->withQueryParameters([$column => $typePart.'fts'.$configPart.'.'.$query]);

		return $this;
	}

	public function match($query)
	{
		foreach ($query as $column => $value) {
			$this->url = $this->url->withQueryParameters([$column => 'eq.'.$value]);
		}

		return $this;
	}

	public function not($column, $operator, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => 'not.'.$operator.'.'.$value]);

		return $this;
	}

	public function or(string $filters, array $options = [])
	{
		$foreignTable = $options['foreignTable'] ?? null;
		$key = $foreignTable ? $foreignTable.'.or' : 'or';
		$this->url = $this->url->withQueryParameters([$key, '('.$filters.')']);

		return $this;
	}

	public function filter($column, $operator, $value)
	{
		$this->url = $this->url->withQueryParameters([$column => $operator.'.'.$value]);

		return $this;
	}
}
