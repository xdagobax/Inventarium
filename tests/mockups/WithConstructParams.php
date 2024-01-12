<?php
namespace DgbAuroCore\lib\Inventarium\tests\mockups;

class WithConstructParams{

    public $a;
    public $b;
    public function __construct($a=true,$b = false){

        $this->a = $a;
        $this->b = $b;
        return $this->a;
    }
    public function testA(){

        return $this->a;
    }
    public function testB(){

        return $this->b;
    }

    public function testget(){

        return 'testget done!';
    }
}