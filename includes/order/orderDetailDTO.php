<?php

namespace TheBalance\order;

/**
 * Data Transfer Object de Detalles de Pedido
 */
class orderDetailDTO implements \JsonSerializable
{
    /**
     * @var int ID del pedido asociado
     */
    private $order_id;

    /**
     * @var int ID del producto
     */
    private $product_name;

    /**
     * @var int ID del producto
     */
    private $image_url;

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
    public function __construct($order_id, $product_name, $image_url, $quantity, $price)
    {
        $this->order_id = $order_id;
        $this->product_name = $product_name;
        $this->image_url = $image_url;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    /**
     * Getters
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    public function getProductName()
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