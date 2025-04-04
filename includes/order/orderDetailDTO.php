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
    private $product_id;

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
     * @var string Talla del producto
     */
    private $size;

    /**
     * Constructor
     */
    public function __construct($order_id, $product_id, $image_url, $quantity, $price, $size)
    {
        $this->order_id = $order_id;
        $this->product_id = $product_id;
        $this->image_url = $image_url;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->size = $size;
    }

    /**
     * Getters
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    public function getProductId()
    {
        return $this->product_id;
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

    public function getSize()
    {
        return $this->size;
    }

    /**
     * Setters
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    public function setProductId($product_name)
    {
        $this->product_name = $product_name;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setSize($size)
    {
        $this->size = $size;
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