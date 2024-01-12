<?php

namespace DgbAuroCore\lib\Inventarium\tests\mockups;

use DgbAuroCore\lib\Inventarium\Facade;


class EventSuscriptor
{
    
    public function suscribe(){
        Facade::delegateEvent($this, 'onEventTrigerOne');
        Facade::delegateEvent($this, 'onEventTrigerTwo');
        Facade::delegateEvent($this, 'onEventTrigerZero');
        return $this;
    }
    
    public function onEventTrigerZero($e)
    {
    
        $e['suscriptorResponse'] = 'onEventTrigerZero recibido';
        
        return $e;
    }
    
    public function onEventTrigerOne($e)
    {
        
        $e['suscriptorResponse'] = 'onEventTrigerOne recibido';
        
        return $e;
    }
    
    public function onEventTrigerTwo($e)
    {
        
        $e['suscriptorResponse'] = 'onEventTrigerTwo recibido';
        
        return $e;
    }
}
