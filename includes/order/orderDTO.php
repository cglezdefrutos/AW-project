<?php

namespace TheBalance\order;

/**
 * Data Transfer Object de Orders
 */
class orderDTO implements \JsonSerializable
{
    /**
     * @var int Identificador del pedido
     */
    private $id;

    /**
     * @var int Email del usuario
     */
    private $user_email;

    /**
     * @var float Precio total del pedido
     */
    private $total_price;

    /**
     * @var string Estado del pedido
     */
    private $status;

    /**
     * @var Date Fecha de creacion
     */
    private $created_at;

    /**
     * Constructor
     */
    public function __construct($id, $user_email, $total_price, $status, $created_at)
    {
        $this->id = $id;
        $this->user_email = $user_email;
        $this->total_price = $total_price;
        $this->status = $status;
        $this->created_at = $created_at;
    }

    /**
     * Getters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUserEmail()
    {
        return $this->user_email;
    }

    public function getTotalPrice()
    {
        return $this->total_price;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Setters
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }

    public function setTotalPrice($total_price)
    {
        $this->total_price = $total_price;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
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