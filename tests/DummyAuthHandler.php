<?php

namespace TestDummies;

use DeltaX\Mantrain\Handler;

class DummyAuthHandler extends Handler {

	protected $username;
	protected $adapter;
	protected $permission;

	public function __construct($username, $permission){

		$this->username = $username;
		$this->permission = $permission;
	}

	public function setAdapter($adapter){

		$this->adapter = $adapter;

		return $this;
	}

	public function process(){

		$allowed = $this->adapter->isAllowed($this->username, $this->permission);

		if ( ! $allowed ) {
			$this->code = 401;
			$this->outputData = ["message" => "Not allowed"];
		}

	}

}

		