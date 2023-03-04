<?php

namespace Supabase\Util;

class PostgrestApiError extends PostgrestError
{
	protected int $status;

	public function __construct($message, $status)
	{
		parent::__construct($message);
		$this->status = $status;
		$this->name = 'PostgrestApiError';
	}
}
