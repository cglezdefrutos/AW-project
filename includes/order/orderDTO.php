<?php

namespace TheBalance\order;

/**
 * Data Transfer Object de Orders
 */
class orderDTO implements \JsonSerializable
{
    /**
     * @var int Identificador del usuario
     */
    private $id;

    /**
     * @var int Id del usuario al que pertenece
     */
    private $user_id;

    /**
     * @var int address_id al que pertenece
     */
    private $address_id;

    /**
     * @var float address_id al que pertenece
     */
    private $total_price;

    /**
     * @var string estado actual del pedido
     */
    private $status;

    /**
     * @var Date Fecha de creacion
     */
    private $created_at;

    /**
     * Constructor
     */
    public function __construct($id, $user_id, $address_id, $total_price, $status, $created_at)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->address_id = $address_id;
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

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getAddressId()
    {
        return $this->address_id;
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

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setAddressId($address_id)
    {
        $this->address_id = $address_id;
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