<?php

namespace DgbAuroCore\vendor\Inventarium\tests\mockups;

use DgbAuroCore\vendor\Inventarium\Facade;


class EventSender
{

    public function justForTriggerEventFunction()
    {
        $senderMsg = 'Evento 0 lanzado desde el sender';
        Facade::trigger(['event' => 'onEventTrigerZero', 'senderMsg' =>$senderMsg]);
        
        $senderMsg = 'Evento 1 lanzado desde el sender';
        Facade::trigger(['event' => 'onEventTrigerOne', 'senderMsg' =>$senderMsg]);
       
        $senderMsg = 'Evento 2 lanzado desde el sender';
        Facade::trigger(['event' => 'onEventTrigerTwo', 'senderMsg' =>$senderMsg]);
      
       
    }
}
