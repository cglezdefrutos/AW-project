<?php
    require_once("IEvent.php");
    require_once("eventFactory.php");
    require_once("eventDTO.php");
    require_once("joinEventDTO.php");

    class eventAppService
    {
        private static $instance;

        // Patrón Singleton para asegurar una única instancia de la clase
        public static function GetSingleton()
        {
            if (!self::$instance instanceof self)
            {
                self::$instance = new self;
            }

            return self::$instance;
        }
      
        private function __construct()
        {
            
        } 

        public function register($eventData)
        {
            $IEventDAO = eventFactory::CreateEvent();

            $eventsDTO = new eventDTO(0, $eventData['name'], $eventData['description'], $eventData['date'], $eventData['price'], $eventData['location'], $eventData['capacity'], $eventData['category'], $eventData['provider']);

            return $IEventDAO->registerEvent($eventsDTO);;
            
        }

        public function search($filters)
        {
            $IEventDAO = eventFactory::CreateEvent();

            $foundedEventsDTO = $IEventDAO->getEvents($filters);

            return $foundedEventsDTO;
        }

        public function joinEvent($data)
        {
            $IEventDAO = eventFactory::CreateEvent();

            $joinEventDTO = new joinEventDTO($data['user_id'], $data['event_id'], $data['user_name'], $data['user_phone']);

            return $IEventDAO->joinEvent($joinEventDTO);
        }

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

        public function getEventById($eventId)
        {
            $IEventDAO = eventFactory::CreateEvent();

            return $IEventDAO->getEventById($eventId);
        }

        public function updateEvent($updatedEventDTO)
        {
            $IEventDAO = eventFactory::CreateEvent();

            return $IEventDAO->updateEvent($updatedEventDTO);
        }

        public function deleteEvent($eventId)
        {
            $IEventDAO = eventFactory::CreateEvent();

            return $IEventDAO->deleteEvent($eventId);
        }
    }
?>