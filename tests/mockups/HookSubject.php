<?php

namespace DgbAuroCore\lib\Inventarium\tests\mockups;

use DgbAuroCore\lib\Inventarium\Facade;


class HookSubject
{
    
   public function getData($dato){

      $dato = Facade::applyFilter('replace_data',$dato);
      return $dato;
   }
  
   public function triggerActionAsEvent($dato){

      $dato .= ' Hook Subject ';
      $actionDoneResult = Facade::doAction('triggerActionAsEvent', $dato);
      return $actionDoneResult. ' triggerActionAsEvent done!!!';
      
   }
}
