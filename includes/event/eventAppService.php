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

            $eventsDTO = new eventDTO($eventData['name'], $eventData['description'], $eventData['date'], $eventData['location'], $eventData['price'], $eventData['capacity'], $eventData['category'], $eventData['provider']);

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
    }  
     
?>