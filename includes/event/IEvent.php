<?php
    interface IEvent
    {
        public function getEvents($eventDTO);
        public function registerEvent($registerEventDTO);
    }
?>