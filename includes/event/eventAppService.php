<?php
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

        public function search($filters)
        {
            $IEventDAO = eventFactory::CreateEvent();

            $foundedEventsDTO = $IEventDAO->getEvents($filters);

            return $foundedEventsDTO;
        }
    }   
?>