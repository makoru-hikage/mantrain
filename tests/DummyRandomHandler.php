<?php

namespace TestDummies;

use DeltaX\Mantrain\Handler;

class DummyRandomHandler extends Handler {
	
	public function process(){

		$this->code = 200;
		$this->outputData = ["data" => "YEHEY!"];

	}
}