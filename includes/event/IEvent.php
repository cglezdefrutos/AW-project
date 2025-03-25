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

    /**
     * Comprueba si un usuario ya está apuntado a un evento
     * 
     * @param int $userId Id del usuario
     * @param int $eventId Id del evento
     * 
     * @return bool Resultado de la operación
     */
    public function isJoined($userId, $eventId);
    
    /**
     * Apunta a un usuario a un evento
     * 
     * @param array $joinEventDTO Datos del usuario y evento
     * 
     * @return bool Resultado de la operación
     */
    public function joinEvent($joinEventDTO);

    /**
     * Obtiene el evento asociado a un id
     * 
     * @param int $eventId Id del evento
     * 
     * @return eventDTO Evento
     */
    public function getEventById($eventId);
    
    /**
     * Comprueba si un usuario es propietario de un evento
     * 
     * @param int $eventId Id del evento
     * @param string $user_email Email del usuario
     * 
     * @return bool Resultado de la operación
     */
    public function ownsEvent($eventId, $userEmail);
    
    /**
     * Obtiene los participantes de un evento
     * 
     * @param int $eventId Id del evento
     * 
     * @return array Participantes
     */
    public function getParticipants($eventId);
    
    /**
     * Actualiza un evento
     * 
     * @param array $eventDTO Datos del evento
     * 
     * @return bool Resultado de la operación
     */
    public function updateEvent($eventDTO);
    
    /**
     * Elimina un evento
     * 
     * @param int $eventId Id del evento
     * 
     * @return bool Resultado de la operación
     */
    public function deleteEvent($eventId);
}