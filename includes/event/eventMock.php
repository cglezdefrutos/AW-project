<?php
    require_once("IEvent.php");
    require_once("eventDTO.php");

    class eventMock implements IEvent
    {
        public function __construct()
        {

        }
        
        public function getEvents($filters = array() )
        {
            // Array de eventDTOs
            $events = array();
            $events[] = new eventDTO(1, "Event 1", "Descripcion 1", "2023-10-01", 10, "Location 1", "Futbol");
            $events[] = new eventDTO(2, "Event 2", "Descripcion 2", "2023-10-05", 20, "Location 2", "Baloncesto");
            $events[] = new eventDTO(3, "Event 3", "Descripcion 3", "2023-10-10", 30, "Location 3", "Fitness");
            $events[] = new eventDTO(4, "Event 4", "Descripcion 4", "2023-10-15", 40, "Location 4", "Conferencias");
            return $events;
        }

 
        public function registerEvent($registerEventDTO)
        {
            // Implementar la logica de acceso a la base de datos para registrar un evento
            return true;
        }

        public function joinEvent($joinEventDTO)
        {
            return true;
        }
        
    }
?>