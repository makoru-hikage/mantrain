<?php

namespace TestDummies;

use DeltaX\Mantrain\Module;

class DummyRandomModule extends Module {
    
    protected function process(){

        $this->code = 200;
        $this->data = ["data" => "YEHEY!"];

    }
}