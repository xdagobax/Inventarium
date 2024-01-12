<?php

namespace DgbAuroCore\lib\Inventarium\tests\mockups;

use DgbAuroCore\lib\Inventarium\Facade;


class MkClass
{

    public $onTestEvent = false;

    public function triggerDelegatedEvents()
    {
        for ($i=0; $i < 3; $i++) { 
            Facade::trigger(['event'=>'onTestEvent','args'=>['Un argumento']]);
                
        }
    }
    public function onTestEvent($e)
    {

        $url = $e['args'][0];
        // echo ("onTestEvent!!!! $url \n");
        $this->onTestEvent = true;
    }
}
