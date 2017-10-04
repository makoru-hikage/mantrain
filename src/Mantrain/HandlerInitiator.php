<?php

namespace DeltaX\Mantrain;

use DeltaX\Mantrain\Handler;

class HandlerInitiator {

	/**
	 * The data to be processed by every handler
	 * 
	 * @var array
	 */
	protected $data;

	/**
	 * Some sort of a flag whether to continue allowing
	 * the data to be modified or not.
	 * 
	 * @var int
	 */
	protected $code;

	/**
	 * The handler it holds
	 * @var \DeltaX\Mantrain\Handler
	 */
	protected $handler;

	/**
	 * The handler class to be initiated
	 * 
	 * @var string
	 */
	protected $handlerClass;

	/**
	 * The Handler's arguments if it is initiated
	 * @var array
	 */
	protected $handlerArguments;

	/**
	 * An array where the keys are method names and 
	 * the values are the corresponding arguments.
	 * It shall be called upon by the handler.
	 * 
	 * @var array
	 */
	protected $handlerAuxilliaries;

	/**
	 * Initiate the initiator
	 * 
	 * @param array       $data
	 * @param int|integer $code
	 */
	public function __construct($data = [], int $code = 0){
		
		$this->data = $data;
		$this->code = $code;
	}

	/**
	 * Set the handler or set the class to be initiated
	 * 
	 * @param string|\DeltaX\Mantrain\Handler $handler
	 * @param mixed $arguments 
	 */
	public function setHandler($handler, ...$arguments){
		
		// If one of the handlers had sent a non-zero code
		//before this.
		if ( $this->code != 0 ){
			return $this;
		}

		switch ($handler) {

			case $handler instanceof Handler:

				$this->handler = $handler;
				$this->handlerClass = get_class($handler);
				break;
			
			case is_string($handler):

				$this->handlerClass = $handler;
				$this->handlerArguments = $arguments;
				break;
		}

		return $this;
	}

	/**
	 * Set and prepare the arguments of the Handler
	 * 
	 * @param mixed $arguments
	 */
	public function setHandlerArguments(...$arguments){

		$this->handlerArguments = $arguments;

		return $this;
	}

	/**
	 * Set a method to be called by the initiated Handler
	 * 
	 * @param string $methodName
	 * @param mixed $arguments 
	 */
	public function set(string $methodName, ...$arguments){
	
		$this->handlerAuxilliaries[$methodName] = $arguments;

		return $this;

	}

	/**
	 * Initiate. Prepare. Run.
	 * 
	 * @return self|\DeltaX\Mantrain\HandlerInitiator
	 */
	public function run(){

		return $this
			->initiateHandler()
			->prepareInputData()
			->runHandlerAuxilliaries()
			->runHandler();
	}

	/**
	 * Initiate the handler
	 * 
	 * @return self
	 */
	protected function initiateHandler(){

		if ( empty($this->handler) && empty($this->handlerClass) ) {
			return $this;
		}

		$handlerClass = $this->handlerClass;
		$arguments = $this->handlerArguments;

		$this->handler = $this->handler ?? new $handlerClass( ...$arguments );

		return $this;
	}

	/**
	 * Set the handler's data
	 * 
	 * @return self
	 */
	protected function prepareInputData(){

		if ( empty($this->handler) ) {
			return $this;
		}

		$this->handler = $this->handler->setInputData($this->data);

		return $this;	
	}

	/**
	 * Called all the methods and supply the corresponding arguments.
	 * 
	 * @return self
	 */
	protected function runHandlerAuxilliaries(){

		if ( empty($this->handler) ) {
			return $this;
		}

		$handler = $this->handler;

		foreach ($this->handlerAuxilliaries as $handlerMethod => $arguments) {

			$handler = $handler->{handlerMethod}(...$arguments);

		}

		$this->handler = $handler;

		return $this;
	}

	/**
	 * run the handler
	 * 
	 * @return self|\DeltaX\Mantrain\HandlerInitiator
	 */
	protected function runHandler(){

		if ( empty($this->handler) ) {
			return $this;
		}

		return $this->handler->run();
	}
	
	public function getData(){

		return $this->data();
	}
	
	public function getCode(){

		return $this->code();
	}

}
