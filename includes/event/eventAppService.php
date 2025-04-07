<?php

namespace TheBalance\event;

use TheBalance\user\userAlreadyJoinEventException;
use TheBalance\application;

/**
 * Clase que contiene la lógica de la aplicación de eventos
 */
class eventAppService
{
    // Patrón Singleton
    /**
     * @var eventAppService Instancia de la clase
     */
    private static $instance;

    /**
     * Devuelve una instancia de {@see eventAppService}.
     * 
     * @return eventAppService Obtiene la única instancia de la <code>eventAppService</code>
     */
    public static function GetSingleton()
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
    /**
     * Evita que se pueda instanciar la clase directamente.
     */
    private function __construct()
    {
        
    } 

    /**
     * Registra un evento
     * 
     * @param array $eventData Datos del evento
     * 
     * @return bool Resultado del registro
     */
    public function register($eventData)
    {
        $IEventDAO = eventFactory::CreateEvent();

        // Comprobamos si el evento ya existe la categoria ya existe
        $categoryId = $IEventDAO->getCategoryId($eventData['category']);

        if ($categoryId === -1)
        {
            // Si no existe, lo registramos
            $categoryId = $IEventDAO->registerCategory($eventData['category']);
        }

        $eventsDTO = new eventDTO(
            null, 
            $eventData['name'], 
            $eventData['description'], 
            $eventData['date'], 
            $eventData['price'], 
            $eventData['location'], 
            $eventData['capacity'], 
            new eventCategoryDTO($categoryId, $eventData['category']),
            $eventData['provider']
        );

        return $IEventDAO->registerEvent($eventsDTO);
    }

    /**
     * Busca eventos
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Resultado de la búsqueda
     */
    public function search($filters)
    {
        $IEventDAO = eventFactory::CreateEvent();

        $foundedEventsDTO = $IEventDAO->getEvents($filters);

        return $foundedEventsDTO;
    }

    /**
     * Registra la asistencia de un usuario a un evento
     * 
     * @param array $data Datos de la asistencia
     * 
     * @return bool Resultado del registro
     */
    public function joinEvent($data)
    {
        $IEventDAO = eventFactory::CreateEvent();

        // Comprobamos si el usuario ya está registrado en el evento
        $isJoined = $IEventDAO->isJoined($data['user_id'], $data['event_id']);

        if ($isJoined)
        {
            throw new userAlreadyJoinEventException("Ya estás registrado en este evento.");
        }

        $joinEventDTO = new joinEventDTO($data['user_id'], $data['event_id'], $data['user_name'], $data['user_phone']);

        return $IEventDAO->joinEvent($joinEventDTO);
    }

    /**
     * Devuelve los eventos asociados al tipo de usuario
     * 
     * @param string $user_type Tipo de usuario
     * 
     * @return array Resultado de la búsqueda
     */
    public function getEventsByUserType()
    {
        $IEventDAO = eventFactory::CreateEvent();
        $eventsDTO = null;

        $app = application::getInstance();

        // Si es administrador, tomamos todos los eventos
        if ($app->isCurrentUserAdmin())
        {
            $eventsDTO = $IEventDAO->getEvents();
        }
        // Si es proveedor, tomamos SOLO los eventos del proveedor
        else 
        {
            // Tomamos el email del proveedor
            $userEmail = htmlspecialchars($app->getCurrentUserEmail());

            // Pasamos como filtro un array con el email (así solo traerá los eventos donde coincida ese email)
            $eventsDTO = $IEventDAO->getEvents(array("email_provider" => $userEmail));
        }

        return $eventsDTO;
    }

    /**
     * Devuelve los evento asociado a ese id
     * 
     * @param string $eventId ID del evento
     * 
     * @return IEventDAO Resultado de la búsqueda
     */
    public function getEventById($eventId)
    {
        $IEventDAO = eventFactory::CreateEvent();

        return $IEventDAO->getEventById($eventId);
    }

    /**
     * Actualiza un evento
     * 
     * @param array $updatedEventDTO Datos del evento actualizado
     * 
     * @return bool Resultado de la actualización
     */
    public function updateEvent($updatedEventDTO)
    {
        $IEventDAO = eventFactory::CreateEvent();

        // Obtener el ID de la categoría a partir del nombre de la categoría
        $categoryId = $IEventDAO->getCategoryId($updatedEventDTO->getCategoryName());

        if ($categoryId === -1)
        {
            // Si no existe, la creamos
            $categoryId = $IEventDAO->registerCategory($eventData['category']);
        }

        // Actualizamos el ID de la categoría en el DTO
        $updatedEventDTO->setCategoryId($categoryId);

        return $IEventDAO->updateEvent($updatedEventDTO);
    }

    /**
     * Elimina un evento
     * 
     * @param string $eventId ID del evento
     * 
     * @return bool Resultado de la eliminación
     */
    public function deleteEvent($eventId)
    {
        $IEventDAO = eventFactory::CreateEvent();

        // Si el evento tiene participantes, no se puede eliminar
        $participants = $IEventDAO->getParticipants($eventId);

        if (count($participants) > 0)
        {
            throw new eventHasParticipantsException("No puedes eliminar un evento que tiene participantes.");
        }

        // Tomamos la instancia de la aplicación
        $app = application::getInstance();

        // Si es administrador, se permite eliminar cualquier evento
        if ($app->isCurrentUserAdmin())
        {
            return $IEventDAO->deleteEvent($eventId);
        }
        // Si es proveedor, solo puede eliminar sus eventos
        else 
        {
            // Tomamos el email del proveedor
            $userEmail = htmlspecialchars($app->getCurrentUserEmail());

            // Comprobamos si el evento pertenece al proveedor
            $owner = $IEventDAO->ownsEvent($eventId, $userEmail);

            if ($owner)
            {
                return $IEventDAO->deleteEvent($eventId);
            }
            else
            {
                throw new notEventOwnerException("No puedes eliminar un evento que no te pertenece.");
            }
        }
    }

    /**
     * Retorna las categorías de eventos
     * 
     * @return eventCategoryDTO[] Lista de categorías de eventos
     */
    public function getEventCategories()
    {
        $IEventDAO = eventFactory::CreateEvent();

        return $IEventDAO->getCategories();
    }

    /**
     * Elimina una categoría de evento
     * 
     * @param string $categoryId ID de la categoría
     * 
     * @return bool Resultado de la eliminación
     */
    public function deleteEventCategory($categoryId)
    {
        $IEventDAO = eventFactory::CreateEvent();

        // Comprobamos si la categoría tiene eventos asociados
        $events = $IEventDAO->getEvents(array("category_id" => $categoryId));

        if (count($events) > 0)
        {
            throw new eventCategoryHasEventsException("No puedes eliminar una categoría que tiene eventos asociados.");
        }

        return $IEventDAO->deleteEventCategory($categoryId);
    }
}
