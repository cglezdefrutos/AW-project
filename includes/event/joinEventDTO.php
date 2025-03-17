<?php

namespace TheBalance\event;

/**
 * Data Transfer Object de apuntarse a un evento
 */
class joinEventDTO implements JsonSerializable
{
    /**
     * @var int Identificador del usuario
     */
    private $userId;

    /**
     * @var int Identificador del evento
     */
    private $eventId;

    /**
     * @var string Nombre del usuario
     */
    private $userName;

    /**
     * @var string Teléfono del usuario
     */
    private $userPhone;

    /**
     * Constructor
     */
    public function __construct($userId, $eventId, $userName, $userPhone)
    {
        $this->userId = $userId;
        $this->eventId = $eventId;
        $this->userName = $userName;
        $this->userPhone = $userPhone;
    }

    
    /**
     * Getters
     */
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

    /**
     * Setters
     */
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

    /**
     * Implementación de JsonSerializable
     * @return array Array con los datos del objeto
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}