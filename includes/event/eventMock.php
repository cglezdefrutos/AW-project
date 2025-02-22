<?php
    require("IEvent.php");

    class eventMock implements IEvent
    {
        public function __construct()
        {

        }
        
        public function getEvents($filters = array() )
        {
            return true;
        }
        
    }
?>