<?php

    class joinEventDTO implements JsonSerializable
    {
        private $userId;
        private $eventId;
        private $username;
        private $userEmail;
        private $userPhone;

        public function __construct($userId, $eventId, $username, $userEmail, $userPhone)
        {
            $this->userId = $userId;
            $this->eventId = $eventId;
            $this->username = $username;
            $this->userEmail = $userEmail;
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

        public function getUsername()
        {
            return $this->username;
        }

        public function getUserEmail()
        {
            return $this->userEmail;
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

        public function setUsername($username)
        {
            $this->username = $username;
        }

        public function setUserEmail($userEmail)
        {
            $this->userEmail = $userEmail;
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