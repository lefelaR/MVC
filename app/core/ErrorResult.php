<?php

enable_noExecute();

class ErrorResult extends Result
{
    /**
     * constructor accepting either a message or an error
     **/
    public function __construct($message = null, $code = 400)
    {
        $this->data = [
            'Status' => [
                'Message' => $message,
                'Code' => $code,
            ],
        ];

        http_response_code($code);
        parent::__construct($this->data);
    }

}