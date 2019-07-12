<?php

namespace TestDummies;

use DeltaX\Mantrain\Module;

class DummyValidatorModule extends Module {

	protected function process(){

		$outputData = [];
		$name = $this->data['name'];
		$email = $this->data['email'];

		if ( empty($name) ) {
			$outputData['name'] = "Name must not be empty";
		}

		if ( !preg_match('/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/', $email) ) {
			$outputData['email'] = "Email is invalid";
		}

		if ( ! empty($outputData) ) {
			$this->code = 400;
			$this->data = ["message" => $outputData];
		}
	}
}