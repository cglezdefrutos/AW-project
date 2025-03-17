<?php

namespace TheBalance\event;

/**
 * Factory de eventos
 */
class eventFactory
{
    /**
     * Crea un DAO de evento
     * 
     * @return IEvent DAO de Evento creado
     */
    public static function CreateEvent() : IEvent
    {
        $eventDAO = false;
        $config = "DAO";

        if ($config === "DAO")
        {
            $eventDAO = new eventDAO();
        }
        else
        {
            $eventDAO = new eventMock();
        }

        return $eventDAO;
    }
}