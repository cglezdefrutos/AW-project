<?php
    require("eventDAO.php");
    require("eventMock.php");

    class eventFactory
    {
        public static function CreateEvent() : IEvent
        {
            $eventDAO = false;
            $config = "";

            if ($config == "DAO")
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
?>