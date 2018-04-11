<?php

namespace buff\classes\services;

use buff\classes\response\ResponseModelInterface;

class ResponseService
{
    private $statusCode = 200;
    private $data = [];
    private $errorCode = 0;
    private $errorMessage = '';
    private $responseData = [];
    private $headers = [];
    private $message;

    public function withStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	public function withErrorCode($errorCode)
	{
		$this->errorCode = $errorCode;

		return $this;
	}

	public function withErrorMessage($errorMessage)
	{
		$this->errorMessage = $errorMessage;

		return $this;
	}

	public function withData($data = [])
	{
		if (!is_array($data) && !$data instanceof ResponseModelInterface) {
			throw new \Exception('Malformed response data.');
		}
		
		if ($data instanceof ResponseModelInterface) {
			$data = $data->expose();
		}
		
		$this->data = $data;
		
		return $this;
	}

	public function getResponse()
	{
		$this->addHeaders();
		
		$this->responseData['data']    = $this->data;
		$this->responseData['message'] = (string) $this->message;
		$this->responseData['error']['errorCode'] = $this->errorCode;
		$this->responseData['error']['errorMessage'] = $this->errorMessage;
		if ($this->statusCode) http_response_code($this->statusCode);
		return json_encode($this->responseData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	}

	public function write()
	{
		return $this->getResponse();
	}

	private function addHeaders()
	{
		foreach ($this->headers as $headerName => $headerValue) {
			header($headerName . ': ' . $headerValue);
		}
	}

	public function withMessage($message)
	{
		$this->message = (string) $message;
		
		return $this;
	}
}