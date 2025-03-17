<?php

namespace TheBalance\event;

/**
 * Interfaz de eventos
 */
interface IEvent
{
    /**
     * Busca eventos
     * 
     * @param array $eventDTO Filtros de búsqueda
     * 
     * @return array Resultado de la búsqueda
     */
    public function getEvents($eventDTO);

    /**
     * Registra un evento
     * 
     * @param array $registerEventDTO Datos del evento
     * 
     * @return bool Resultado del registro
     */
    public function registerEvent($registerEventDTO);
}