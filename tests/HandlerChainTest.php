<?php

use PHPUnit\Framework\TestCase;
use DeltaX\Mantrain\HandlerInitiator;
use TestDummies\DummyValidatorHandler;
use TestDummies\DummyAuthHandler;
use TestDummies\DummyRandomHandler;
use TestDummies\DummyAuthAdapter;

class HandlerChainTest extends TestCase{

	public function testChainSuccess(){

		$input = [ "name" => "cmoran", "email" => "cmoran@gmail.com" ];
		
		$mantrain = new HandlerInitiator($input);

		$mantrain = $mantrain
			->setHandler(DummyValidatorHandler::class)
			->run()
			->setHandler(DummyAuthHandler::class)
			->setHandlerArguments("brando", "contact.create")
			->set('setAdapter', new DummyAuthAdapter)
			->run()
			->setHandler(DummyRandomHandler::class)
			->run();

		$this->assertEquals(200, $mantrain->getCode());
		$this->assertEquals(["data" => "YEHEY!"], $mantrain->getData());

	}

	public function testInputWasNotValid(){

		$input = [ "name" => "cmoran", "email" => "cmorangmail.com" ];
		
		$mantrain = new HandlerInitiator($input);

		$mantrain = $mantrain
			->setHandler(DummyValidatorHandler::class)
			->run()
			->setHandler(DummyAuthHandler::class)
			->setHandlerArguments("brando", "contact.create")
			->set('setAdapter', new DummyAuthAdapter)
			->run()
			->setHandler(DummyRandomHandler::class)
			->run();

		$this->assertEquals(400, $mantrain->getCode());
		$this->assertEquals(["message" => [ "email" => "Email is invalid" ] ], $mantrain->getData());

	}

	public function testNotAllowed(){

		$input = [ "name" => "cmoran", "email" => "cmoran@gmail.com" ];
		
		$mantrain = new HandlerInitiator($input);
		$user = "brando";
		$action = "contact.delete";

		$mantrain = $mantrain
			->setHandler(DummyValidatorHandler::class)
			->run()
			->setHandler(DummyAuthHandler::class)
			->setHandlerArguments($user, $action)
			->set('setAdapter', new DummyAuthAdapter)
			->run()
			->setHandler(DummyRandomHandler::class)
			->run();

		$this->assertEquals(401, $mantrain->getCode());
		$this->assertEquals(["message" => "Not allowed"], $mantrain->getData());

	}


}