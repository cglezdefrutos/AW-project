<?php

namespace TheBalance\user;

/**
 * Data Transfer Object de usuario
 */
class userDTO implements \JsonSerializable
{
    /**
     * @var int Identificador del usuario
     */
    private $id;

    /**
     * @var string Email del usuario
     */
    private $email;

    /**
     * @var string Contraseña del usuario
     */
    private $password;

    /**
     * @var int Tipo de usuario
     */
    private $usertype;

    /**
     * Constructor
     */
    public function __construct($id, $email, $password, $usertype)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->usertype = $usertype;
    }

    /**
     * Getters
     */
    public function id()
    {
        return $this->id;
    }

    public function email()
    {
        return $this->email;
    }

    public function password()
    {
        return $this->password;
    }

    public function usertype()
    {
        return $this->usertype;
    }

    /**
     * Setters
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setUserType($usertype)
    {
        $this->usertype = $usertype;
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