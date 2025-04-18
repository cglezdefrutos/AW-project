<?php

namespace TheBalance\event;

/**
 * Data Transfer Object de un evento
 */
class eventDTO implements \JsonSerializable
{
    // Atributos
    /**
     * @var int Identificador del evento
     */
    private $id;

    /**
     * @var string Nombre del evento
     */
    private $name;
    
    /**
     * @var string Descripción del evento
     */
    private $desc;

    /**
     * @var string Fecha del evento
     */
    private $date;
    
    /**
     * @var float Precio del evento
     */
    private $price;
    
    /**
     * @var string Ubicación del evento
     */
    private $location;

    /**
     * @var eventCategoryDTO Objeto que contiene la categoría del evento
     * @see eventCategoryDTO
     */
    private $categoryDTO;

    /**
     * @var int Capacidad del evento
     */
    private $capacity;

    /**
     * @var string Email del proveedor del evento
     */
    private $email_provider;

    /**
     * Constructor
     */
    public function __construct($id, $name, $desc, $date, $price, $location, $capacity, $categoryDTO, $email_provider)
    {
        $this->id = $id;
        $this->name = $name;
        $this->desc = $desc;
        $this->date = $date;
        $this->price = $price;
        $this->location = $location;
        $this->capacity = $capacity;
        $this->categoryDTO = $categoryDTO;
        $this->email_provider = $email_provider;
    }

    /**
     * Getters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDesc()
    {
        return $this->desc;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getCategoryId()
    {
        return $this->categoryDTO->getId();
    }

    public function getCategoryName()
    {
        return $this->categoryDTO->getName();
    }

    public function getCapacity()
    {
        return $this->capacity;
    }

    public function getEmailProvider()
    {
        return $this->email_provider;
    }

    /**
     * Setters
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function setCategoryDTO($categoryDTO)
    {
        $this->categoryDTO = $categoryDTO;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryDTO->setId($categoryId);
    }
    
    public function setCategoryName($categoryName)
    {
        $this->categoryDTO->setName($categoryName);
    }   

    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    public function setEmailProvider($email_provider)
    {
        $this->email_provider = $email_provider;
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