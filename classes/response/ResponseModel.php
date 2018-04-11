<?php

namespace buff\classes\response;

abstract class ResponseModel implements ResponseModelInterface
{
	protected $hidden = [];
	
	private function filter($key){
		return !in_array($key, $this->hidden);
	}
	
	public function expose(){
		return array_filter($this->toArray(), array($this, 'filter'), ARRAY_FILTER_USE_KEY);
	}
}