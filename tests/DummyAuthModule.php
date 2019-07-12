<?php

namespace TestDummies;

use DeltaX\Mantrain\Module;

class DummyAuthModule extends Module {

    protected $username;
    protected $adapter;
    protected $permission;

    public function __construct($data, $username, $permission){

        parent::__construct($data);
        $this->username = $username;
        $this->permission = $permission;
    }

    public function setAdapter($adapter){

        $this->adapter = $adapter;

        return $this;
    }

    protected function process(){

        $allowed = $this->adapter->isAllowed($this->username, $this->permission);

        if ( ! $allowed ) {
            $this->code = 401;
            $this->data = ["message" => "Not allowed"];
        }

    }

}

        