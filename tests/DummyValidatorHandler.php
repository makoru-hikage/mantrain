<?php

namespace TestDummies;

use DeltaX\Mantrain\Handler;

class DummyValidatorHandler extends Handler {

	public function process(){

		$outputData = [];
		$name = $this->inputData['name'];
		$email = $this->inputData['email'];

		if ( empty($name) ) {
			$outputData['name'] = "Name must not be empty";
		}

		if ( !preg_match('/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/', $email) ) {
			$outputData['email'] = "Email is invalid";
		}

		if ( ! empty($outputData) ) {
			$this->code = 400;
			$this->outputData = ["message" => $outputData];
		}

	}
}