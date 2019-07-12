<?php

use PHPUnit\Framework\TestCase;
use TestDummies\DummyValidatorModule;
use TestDummies\DummyAuthModule;
use TestDummies\DummyRandomModule;
use TestDummies\DummyAuthAdapter;

class ModuleChainTest extends TestCase{

    public function testChainSuccess(){

        $input = [ "name" => "cmoran", "email" => "cmoran@gmail.com" ];

        $mantrain = new DummyValidatorModule($input);

        $mantrain = $mantrain
            ->run(DummyAuthModule::class, ["brando", "contact.create"])
            ->setAdapter(new DummyAuthAdapter)
            ->run(DummyRandomModule::class)
            ->run();

        $this->assertEquals(200, $mantrain->getCode());
        $this->assertEquals(["data" => "YEHEY!"], $mantrain->getData());

    }

    public function testInputWasNotValid(){

        $input = [ "name" => "cmoran", "email" => "cmorangmail.com" ];

        $mantrain = new DummyValidatorModule($input);

        $mantrain = $mantrain
            ->run(DummyAuthModule::class, ["brando", "contact.create"])
            ->setAdapter(new DummyAuthAdapter)
            ->run(DummyRandomModule::class)
            ->run();

        $this->assertEquals(400, $mantrain->getCode());
        $this->assertEquals(["message" => [ "email" => "Email is invalid" ] ], $mantrain->getData());

    }

    public function testNotAllowed(){

        $input = [ "name" => "cmoran", "email" => "cmoran@gmail.com" ];

        $mantrain = new DummyValidatorModule($input);
        $user = "brando";
        $action = "contact.delete";

        $mantrain = $mantrain
            ->run(DummyAuthModule::class, [$user, $action])
            ->setAdapter(new DummyAuthAdapter)
            ->run(DummyRandomModule::class)
            ->run();

        $this->assertEquals(401, $mantrain->getCode());
        $this->assertEquals(["message" => "Not allowed"], $mantrain->getData());

    }

    public function testDislinked(){

        $input = [ "name" => "cmoran", "email" => "cmoran@gmail.com" ];

        $mantrain = new DummyValidatorModule($input);
        $user = "brando";
        $action = "contact.delete";

        $validation = $mantrain;

        $authorization = $validation
            ->run(DummyAuthModule::class, [$user, $action])
            ->setAdapter(new DummyAuthAdapter);


        $final_step = $authorization
            ->run(DummyRandomModule::class)
            ->run();

        $this->assertEquals(401, $final_step->getCode());
        $this->assertEquals(["message" => "Not allowed"], $final_step->getData());

    }


}