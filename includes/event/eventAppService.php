<?php

namespace TheBalance\event;

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

        $eventsDTO = new eventDTO(0, $eventData['name'], $eventData['description'], $eventData['date'], $eventData['price'], $eventData['location'], $eventData['capacity'], $eventData['category'], $eventData['provider']);

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
    public function getEventsByUserType($user_type)
    {
        $IEventDAO = eventFactory::CreateEvent();
        $eventsDTO = null;

        // Si es administrador, tomamos todos los eventos
        if ($user_type == 0)
        {
            $eventsDTO = $IEventDAO->getEvents();
        }
        // Si es proveedor, tomamos SOLO los eventos del proveedor
        else 
        {
            // Tomamos el email del proveedor
            $userDTO = json_decode($_SESSION["user"], true);
            $user_email = htmlspecialchars($userDTO["email"]);

            // Pasamos como filtro un array con el email (así solo traerá los eventos donde coincida ese email)
            $eventsDTO = $IEventDAO->getEvents(array("email_provider" => $user_email));
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

        // Tomamos el tipo de usuario
        $userDTO = json_decode($_SESSION["user"], true);
        $user_type = htmlspecialchars($userDTO["usertype"]);

        // Si es administrador, se permite eliminar cualquier evento
        if ($user_type == 0)
        {
            return $IEventDAO->deleteEvent($eventId);
        }
        // Si es proveedor, solo puede eliminar sus eventos
        else 
        {
            // Tomamos el email del proveedor
            $user_email = htmlspecialchars($userDTO["email"]);

            // Comprobamos si el evento pertenece al proveedor
            $owner = $IEventDAO->ownsEvent($eventId, $user_email);

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
}
