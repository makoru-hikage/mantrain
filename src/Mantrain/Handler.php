<?php

namespace DeltaX\Mantrain;

use DeltaX\Mantrain\HandlerInitiator;

abstract class Handler {

	/**
	 * The data to be used by this through `process`
	 * 
	 * @var array
	 */
	protected $inputData = [];

	/**
	 * A variable to hold whatever results from `process`
	 * 
	 * @var array
	 */
	protected $outputData = [];

	/**
	 * Something to be set through `process`
	 * 
	 * @var int
	 */
	protected $code;

	/**
	 * A setter for input data
	 * 
	 * @param array $input
	 * @return array self
	 */
	public function setInputData(array $input){

		$this->inputData = $input;

		return $this;
	}

	/**
	 * Do the `process` and move on to the next link/Handler
	 * @return [type] [description]
	 */
	public function run(){

		$this->process();

		$data = $this->outputData;
		$code = $this->code ? $this->code : 0;

		return new HandlerInitiator($data, $code);		
	}

	/**
	 * Whatever the descendants do.
	 * This determines the $code and $outputData
	 * 
	 * @return self
	 */
	abstract protected function process();

}