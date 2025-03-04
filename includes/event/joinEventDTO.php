<?php

    class joinEventDTO implements JsonSerializable
    {
        private $userId;
        private $eventId;
        private $userName;
        private $userPhone;

        public function __construct($userId, $eventId, $userName, $userPhone)
        {
            $this->userId = $userId;
            $this->eventId = $eventId;
            $this->userName = $userName;
            $this->userPhone = $userPhone;
        }

        public function getUserId()
        {
            return $this->userId;
        }

        public function getEventId()
        {
            return $this->eventId;
        }

        public function getUserName()
        {
            return $this->userName;
        }

        public function getUserPhone()
        {
            return $this->userPhone;
        }   

        public function setUserId($userId)
        {
            $this->userId = $userId;
        }

        public function setEventId($eventId)
        {
            $this->eventId = $eventId;
        }

        public function setUserName($username)
        {
            $this->username = $username;
        }

        public function setUserPhone($userPhone)
        {
            $this->userPhone = $userPhone;
        }

        public function jsonSerialize()
        {
            return get_object_vars($this);
        }
    }

?>