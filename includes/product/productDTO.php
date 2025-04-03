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
     * @var int Email del proveedor propietario del producto
     */
    private $provider_email;

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
     * @var productCategoryDTO Objeto que contiene la categoría del producto
     * @see productCategoryDTO
     */
    private $category_DTO;

    /**
     * @var string URL de la imagen del producto
     */
    private $image_guid;

    /**
     * @var string Fecha de creación del producto
     */
    private $created_at;

    /**
     * @var productSizesDTO Objeto que contiene las tallas del producto
     * @see productSizesDTO
     */
    private $sizes_DTO;

    /**
     * @var bool Variable que controla si esta activo o no el producto
     */
    private $active;

    /**
     * Constructor
     */
    public function __construct($id, $provider_email, $name, $description, $price, $category_DTO, $image_guid, $created_at, $sizes_DTO, $active)
    {
        $this->id = $id;
        $this->provider_email = $provider_email;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->category_DTO = $category_DTO;
        $this->image_guid = $image_guid;
        $this->created_at = $created_at;
        $this->sizes_DTO = $sizes_DTO;
        $this->active = $active;
    }

    /**
     * Getters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getProviderEmail()
    {
        return $this->provider_email;
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

    public function getCategoryId()
    {
        return $this->category_DTO->getId();
    }

    public function getCategoryName()
    {
        return $this->category_DTO->getName();
    }

    public function getImageGuid()
    {
        return $this->image_guid;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getSizesDTO()
    {
        return $this->sizes_DTO;
    }

    public function getTotalStock()
    {
        return $this->sizes_DTO->getTotalStock();
    }

    public function getActive()
    {
        return $this->active;
    }

    public function getStockBySize($size){
        return $this->sizes_DTO->getStockBySize($size);
    }

    /**
     * Setters
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setProviderEmail($provider_email)
    {
        $this->provider_email = $provider_email;
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

    public function setCategoryId($category_id)
    {
        $this->category_DTO->setId($category_id);
    }

    public function setCategoryName($category_name)
    {
        $this->category_DTO->setName($category_name);
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setSizesDTO($sizes_DTO)
    {
        $this->sizes_DTO = $sizes_DTO;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Implementación de JsonSerializable
     * @return array Array con los datos del objeto
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);

        /* return [
            'id' => $this->id,
            'provider_email' => $this->provider_email,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_DTO' => $this->category_DTO ? $this->category_DTO->jsonSerialize() : null, // Serializar el objeto category
            'image_url' => $this->image_url,
            'sizes_DTO' => $this->sizes_DTO ? $this->sizes_DTO->jsonSerialize() : null, // Serializar el objeto sizesDTO
            'created_at' => $this->created_at,
            'active' => $this->active
        ]; */
    }
}