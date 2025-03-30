<?php

namespace TheBalance\product;

/**
 * Data Transfer Object de producto
 */
class productDTO implements \JsonSerializable
{
    /**
     * @var int Identificador del producto
     */
    private $id;

    /**
     * @var int Identificador del proveedor propietario del producto
     */
    private $provider_id;

    /**
     * @var string Nombre del producto
     */
    private $name;    

    /**
     * @var string Descripción del producto
     */
    private $description;

    /**
     * @var float Precio del producto
     */
    private $price;

    /**
     * @var int Stock del producto
     */
    private $stock;

    /**
     * @var int Identificador de la categoría del producto
     */
    private $category_id;

    /**
     * @var string URL de la imagen del producto
     */
    private $image_url;

    /**
     * @var string Fecha de creación del producto
     */
    private $created_at;

    /**
     * @var array Tallas del producto
     */
    private $sizes = [];

    /**
     * Constructor
     */
    public function __construct($id, $provider_id, $name, $description, $price, $stock, $category_id, $image_url, $created_at, $sizes = [])
    {
        $this->id = $id;
        $this->provider_id = $provider_id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->category_id = $category_id;
        $this->image_url = $image_url;
        $this->created_at = $created_at;
        $this->sizes = $sizes;
    }

    /**
     * Getters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getProviderId()
    {
        return $this->provider_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * Setters
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setProviderId($provider_id)
    {
        $this->provider_id = $provider_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setSizes($sizes)
    {
        $this->sizes = $sizes;
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
