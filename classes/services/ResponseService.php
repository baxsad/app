<?php

namespace buff\classes\services;

use buff\classes\response\ResponseModelInterface;

class ResponseService
{
    private $success = true;
    private $data = [];
    private $errorCode = 0;
    private $errorMessage = '';
    private $responseData = [];
    private $message;

    public function withSuccess()
	{
		$this->success = true;

		return $this;
	}

	public function withFailure()
	{
		$this->success = false;

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

	public function write()
	{
		return $this->getResponse();
	}

	public function withMessage($message)
	{
		$this->message = (string) $message;
		
		return $this;
	}

	public function getResponse()
	{
		$this->responseData['success'] = $this->success;
		$this->responseData['message'] = $this->getMessage();
		$this->responseData['data']    = $this->data;
		$this->responseData['error']['errorCode'] = $this->errorCode;
		$this->responseData['error']['errorMessage'] = $this->errorMessage;
		$this->responseData['time']    = time();
		
		return json_encode($this->responseData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	}

	public function getMessage(): string
	{
        if (empty($this->message)) {
        	return $this->success ? 'success!' : 'failure!';
        }

        return $this->message;
	}
}