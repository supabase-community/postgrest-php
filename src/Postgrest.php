<?php declare(strict_types=1);

class Postgrest {
    private $method;
    public $url;
    private $headers;
    private $body;
    private $schema;
    private $shouldThrowOnError;
    private $signal;
    private $allowEmpty;

    public function __construct($opts) {
        $this->method = isset($opts['method']) && in_array($opts['method'], array('GET', 'POST', 'PATCH', 'PUT', 'DELETE')) && $opts['method'];
        $this->url = $opts['url'];
        $this->headers = isset($opts['headers']) ? $opts['headers'] : [];
        $this->schema = isset($opts['schema']) && $opts['schema'];
        $this->shouldThrowOnError = isset($opts['shouldThrowOnError']) && $opts['shouldThrowOnError'];
        $this->signal = isset($opts['signal']) && $opts['signal'];
        $this->allowEmpty = isset($opts['allowEmpty']) && $opts['allowEmpty'];
        $this->body = isset($opts['body']) && $opts['body'];
    }

    public function execute() {

        if($this->schema) {
            if ($this->method == 'GET' || $this->method == 'HEAD') {
                $this->headers[] = 'Accept-Profile: '.$this->schema;
            } else {
                $this->headers['Content-Profile'] = $this->schema;
            }
        }

        if ($this->method != 'GET' || $this->method != 'HEAD') {
            $this->headers['Content-Type'] = 'application/json';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);

        //echo $this->headers;
        

        if(isset($this->headers) && is_array($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }
        if($this->body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
        }

        if(!$response = curl_exec($ch)) {
            if($this->shouldThrowOnError) {
                throw new Exception('Curl error: ' . curl_error($ch));
            } else {
                $err = curl_error($ch);
                return new PostgrestResponse(null, array(
                    'message' => $err,
                    'details' => '',
                    'hint' => '',
                    'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE) || '',
                ), null, curl_getinfo($ch, CURLINFO_HTTP_CODE));
            }
        
        }
        curl_close($ch);

        $error;
        $data;
        $count;
        $status;
        $statusText;

        $body = $response->text;
        

        if($response->ok) {
            if($this->method == 'HEAD') {
                if($body != '') {
                    if($this->headers['Accept'] == 'text/csv') {
                        $data = $body;
                    } elseif($this->headers['Accept'] && strpost($this->headers['Accept'], 'application/vnd.pgrst.plan+text') !== false) {
                        $data = $body;
                    } else {
                        $data = json_decode($body);
                    }
                }
            }

            $countHeader = $this->headers['Prefer'] && preg_match('/count=(exact|planned|estimated)/', $this->headers['Prefer'], 'count=exact');
            $contentRange = $response->headers->get('Content-Range');
            if($countHeader && $contentRange) {
                $ranges = explode('/', $contentRange);
                if(count($ranges) > 1) {
                    $count = $ranges[1];
                }
            }
        } else {
            try {
                $error = json_decode($body);
            } catch (Exception $e) {
                $error = array( 'message' => $body );
            }

            if($error && $this->allowEmpty && strpos($error->details, 'Results contain 0 rows')) {
                $error = null;
                $status = 200;
                $statusText = 'OK';
            }

            if($error && $this->shouldThrowOnError) {
                throw new Error($error->message);
            }
        }

        $postgrestResponse = new PostgrestResponse($error, $data, $count, $status, $statusText);

        return $postgrestResponse;
    
    }
}

class PostgrestResponse {
    public function __construct($data = '', $error, $count = 0, $status = 0, $statusText = '') {
        $this->data = $data;
        $this->error = $error;
        $this->count = $count;
        $this->status = $status;
        $this->statusText = $statusText;
    }
}