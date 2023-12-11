<?php

namespace DgbAuroCore\vendor\Inventarium\tests\mockups;

use DgbAuroCore\vendor\Inventarium\Facade;


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
