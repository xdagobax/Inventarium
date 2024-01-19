<?php
// TODO ¿Y el namespace?
use DgbAuroCore\vendor\Inventarium\Facade;
use TestCore\UnitTester;

class EventsCest
{
    function __construct()
    {
        require_once __DIR__ . '/../../../DgbDebugger/debug.php';
    }

    function testEvents(UnitTester $I)
    {
        require __DIR__ . '/../../loader.php';
        require __DIR__ . '/../../aliases.php';
        Facade::removeAllEvent();


        Facade::call('EventSuscriptor')->suscribe();

        Facade::call('EventSuscriptorTwo')->suscribe();
       
        //Una sola llamada desata todos los eventos, en este caso multiples en dos objetos distintos
        Facade::call('EventSender')->justForTriggerEventFunction(); 

        $response = Facade::getEventResponses();
        
        //El segundo evento suscrito se ejecutara primero por la prioridad definida en su suscripcion (en este caso es 11 contra la 10 por default que tiene el mismo evento en el primer suscriptor)
        $I->assertStringContainsString('EventSuscriptorTwo', $response[0]->event['suscriptor'],  'Primer evento '.$response[0]->event['response']['suscriptorResponse'] );

        //los siguientes eventos se ejecutan en el orden que fueron suscritos ya que tienen la misma prioridad por defecto (10)
        $I->assertStringContainsString('onEventTrigerZero recibido', $response[1]->event['response']['suscriptorResponse'] ,  'Evento: ' . $response[1]->event['response']['suscriptorResponse']);
        
        $I->assertStringContainsString('onEventTrigerOne recibido', $response[2]->event['response']['suscriptorResponse'] ,  'Evento: ' . $response[2]->event['response']['suscriptorResponse']);
    
    }
}
