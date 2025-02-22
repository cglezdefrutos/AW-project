<?php
    //include __DIR__ . "/../config.php";
    require_once("IEvent.php");
    require_once("eventDTO.php");

    class eventDAO implements IEvent
    {
        public function __construct()
        {

        }
        
        public function getEvents($filters = array() )
        {
            // Implementar la logica de acceso a la base de datos para obtener los eventos
        }
        
    }
?>