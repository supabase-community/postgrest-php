<?php

use Supabase\Util\Request;

class Postgrest
{
    private $method;
    public $url;
    private $headers;
    private $body;
    private $schema;
    private $shouldThrowOnError;
    private $signal;
    private $allowEmpty;
    private $reference_id;
    private $api_key;

    public function __construct($reference_id, $api_key, $opts)
    {
        $this->method = (isset($opts['method']) && in_array($opts['method'], ['GET', 'POST', 'PATCH', 'PUT', 'DELETE'])) ? $opts['method'] : null;
        $this->url = isset($opts['url']) ? $opts['url'] : "https://{$reference_id}.supabase.co/rest/v1";
        $this->headers = isset($opts['headers']) ? $opts['headers'] : [];
        $this->schema = isset($opts['schema']) && $opts['schema'];
        $this->shouldThrowOnError = isset($opts['shouldThrowOnError']) && $opts['shouldThrowOnError'];
        $this->signal = isset($opts['signal']) && $opts['signal'];
        $this->allowEmpty = isset($opts['allowEmpty']) && $opts['allowEmpty'];
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);

        print_r($this->url->__toString());

        $data = Request::request($this->method, $this->url->__toString(), $this->headers, json_encode($this->body));

        return $data;

        //echo $this->headers;

        if (isset($this->headers) && is_array($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }
        if ($this->body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
        }

        $response = curl_exec($ch);

        //return $response;

        if (!$response) {
            if ($this->shouldThrowOnError) {
                throw new Exception('Curl error: '.curl_error($ch));
            } else {
                $err = curl_error($ch);

                return new PostgrestResponse(null, [
                    'message' => $err,
                    'details' => '',
                    'hint'    => '',
                    'code'    => curl_getinfo($ch, CURLINFO_HTTP_CODE) || '',
                ], null, curl_getinfo($ch, CURLINFO_HTTP_CODE));
            }
        }
        //$result = curl_exec( $ch );
        curl_close($ch);

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerStr = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        $error = null;
        $data = null;
        $count = 0;
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $statusText = $body;

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200) {
            if ($this->method == 'HEAD') {
                if ($body != '') {
                    if (isset($this->headers['Accept'])) {
                        if ($this->headers['Accept'] == 'text/csv') {
                            $data = $body;
                        } elseif ($this->headers['Accept'] && strpost($this->headers['Accept'], 'application/vnd.pgrst.plan+text') !== false) {
                            $data = $body;
                        }
                    } else {
                        $data = json_decode($body);
                    }
                }
            }

            $headers = $this->headersToArray($headerStr);

            //return $headers;

            $countHeader = isset($this->headers['Prefer']) && preg_match('/count=(exact|planned|estimated)/', $this->headers['Prefer'], 'count=exact');
            $contentRange = $headers['content-range'];
            if ($countHeader && $contentRange) {
                $ranges = explode('/', $contentRange);
                if (count($ranges) > 1) {
                    $count = $ranges[1];
                }
            }
        } else {
            try {
                $error = json_decode($body);
            } catch (Exception $e) {
                $error = ['message' => $body];
            }

            if ($error && $this->allowEmpty && strpos($error->details, 'Results contain 0 rows')) {
                $error = null;
                $status = 200;
                $statusText = 'OK';
            }

            if ($error && $this->shouldThrowOnError) {
                throw new Error($error->message);
            }
        }

        $postgrestResponse = new PostgrestResponse($error, $data, $count, $status, $statusText);

        return $postgrestResponse;
    }

    public function headersToArray($str)
    {
        $headers = [];
        $headersTmpArray = explode("\r\n", $str);
        for ($i = 0; $i < count($headersTmpArray); $i++) {
            // we dont care about the two \r\n lines at the end of the headers
            if (strlen($headersTmpArray[$i]) > 0) {
                // the headers start with HTTP status codes, which do not contain a colon so we can filter them out too
                if (strpos($headersTmpArray[$i], ':')) {
                    $headerName = substr($headersTmpArray[$i], 0, strpos($headersTmpArray[$i], ':'));
                    $headerValue = substr($headersTmpArray[$i], strpos($headersTmpArray[$i], ':') + 1);
                    $headers[$headerName] = $headerValue;
                }
            }
        }

        return $headers;
    }
}

class PostgrestResponse
{
    public function __construct($data = '', $error, $count = 0, $status = 0, $statusText = '')
    {
        $this->data = $data;
        $this->error = $error;
        $this->count = $count;
        $this->status = $status;
        $this->statusText = $statusText;
    }
}
