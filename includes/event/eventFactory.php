<?php
    require_once("IEvent.php");
    require_once("eventDAO.php");
    require_once("eventMock.php");

    class eventFactory
    {
        public static function CreateEvent() : IEvent
        {
            $eventDAO = false;
            $config = "DAO";

            if ($config === "DAO")
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