<?php

namespace TheBalance\product;

/**
 * Data Transfer Object de las categorías de eventos
 */
class productCategoryDTO implements \JsonSerializable
{
    // Atributos
    /**
     * @var int Identificador de la categoría
     */
    private $id;

    /**
     * @var string Nombre de la categoría
     */
    private $name;

   /**
     * Constructor
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
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

    /**
     * Método para serializar el objeto a JSON
     * 
     * @return array Datos del objeto en formato JSON
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}