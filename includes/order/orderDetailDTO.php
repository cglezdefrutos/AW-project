<?php

namespace TheBalance\order;

/**
 * Data Transfer Object de Detalles de Pedido
 */
class orderDetailDTO implements \JsonSerializable
{
    /**
     * @var int Identificador del detalle
     */
    private $id;

    /**
     * @var int ID del pedido asociado
     */
    private $order_id;

    /**
     * @var int ID del producto
     */
    private $product_name; //antes $product_id

    /**
     * @var int ID del producto
     */
    private $image_url; //antes $product_id

    /**
     * @var int Cantidad del producto
     */
    private $quantity;

    /**
     * @var float Precio unitario
     */
    private $price;

    /**
     * Constructor
     */
    public function __construct($id, $order_id, $product_name, $image_url, $quantity, $price) //antes $product_id
    {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->product_name = $product_name; //antes $product_id
        $this->image_url = $image_url;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    /**
     * Getters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function getProductName() //antes $product_id
    {
        return $this->product_name;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Calcula el subtotal
     * @return float
     */
    public function getSubtotal()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Implementación de JsonSerializable
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}