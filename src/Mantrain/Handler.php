<?php

namespace DeltaX\Mantrain;

use DeltaX\Mantrain\HandlerInitiator;

abstract class Handler {

	protected $inputData;

	protected $outputData;

	protected $code;

	public function setInputData(array $input){

		$this->inputData = $input;

		return $this;
	}

	public function run(){

		$this->process();

		$data = $this->outputData;
		$code = $this->code;

		return new HandlerInitiator($data, $code);		
	}

	abstract protected function process();

}