<?php

namespace DgbAuroCore\lib\Inventarium\tests\mockups;

use DgbAuroCore\lib\Inventarium\Facade;


class EventSuscriptorTwo
{
    
    public function suscribe(){
        Facade::delegateEvent($this, 'onEventTrigerZero',11);
        return $this;
    }
    
   
    public function onEventTrigerZero($e)
    {
        
        $e['suscriptorResponse'] = 's2 onEventTrigerZero recibido';
        
        return $e;

    }
    

}
