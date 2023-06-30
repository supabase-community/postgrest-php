<?php

namespace Supabase\Postgrest;

use Supabase\Postgrest\Util\PostgrestError;
use Supabase\Postgrest\Util\Request;

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