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
     * @var int Identificador del usuario
     */
    private $user_id;

    /**
     * @var float Precio total del pedido
     */
    private $total_price;

    /**
     * @var string Estado del pedido
     */
    private $status;

    /**
     * @var string Direccion de envio al que pertenece
     */
    private $shipping_address;

    /**
     * @var Date Fecha de creacion
     */
    private $created_at;

    /**
     * Constructor
     */
    public function __construct($id, $user_id, $total_price, $status, $shipping_address, $created_at)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->total_price = $total_price;
        $this->status = $status;
        $this->shipping_address = $shipping_address;
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

    public function getTotalPrice()
    {
        return $this->total_price;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getShippingAddress()
    {
        return $this->shipping_address;
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

    public function setTotalPrice($total_price)
    {
        $this->total_price = $total_price;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setShippingAddress($shipping_address)
    {
        $this->shipping_address = $shipping_address;
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