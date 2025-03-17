<?php

namespace TheBalance\event;

class eventMock implements IEvent
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }
    
    /**
     * Devuelve los eventos
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Resultado de la búsqueda
     */
    public function getEvents($filters = array() )
    {
        // Simulamos devolver un Array de eventDTOs
        $events = array();
        $events[] = new eventDTO(1, "Event 1", "Descripcion 1", "2023-10-01", 10, "Location 1", "Futbol");
        $events[] = new eventDTO(2, "Event 2", "Descripcion 2", "2023-10-05", 20, "Location 2", "Baloncesto");
        $events[] = new eventDTO(3, "Event 3", "Descripcion 3", "2023-10-10", 30, "Location 3", "Fitness");
        $events[] = new eventDTO(4, "Event 4", "Descripcion 4", "2023-10-15", 40, "Location 4", "Conferencias");
        return $events;
    }

    /**
     * Registra un evento
     * 
     * @param EventDTO $eventDTO Evento
     * 
     * @return boolean Estado del registro
     */
    public function registerEvent($registerEventDTO)
    {
        // Simulamos que devuelve true
        return true;
    }

    /**
     * Te apunta a un evento
     * 
     * @param joinEventDTO $joinEventDTO Evento
     * 
     * @return boolean Estado del registro
     */
    public function joinEvent($joinEventDTO)
    {
        // Simulamos que devuelve true
        return true;
    }
    
}