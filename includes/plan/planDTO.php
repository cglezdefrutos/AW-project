<?php

namespace TheBalance\plan;

/**
 * Data Transfer Object para planes de entrenamiento
 */
class planDTO implements \JsonSerializable
{
    /**
     * @var int Identificador del plan
     */
    private $id;

    /**
     * @var int ID del entrenador propietario del plan
     */
    private $trainer_id;

    /**
     * @var string Nombre del plan de entrenamiento
     */
    private $name;    

    /**
     * @var string Descripción del plan
     */
    private $description;

    /**
     * @var string Nivel de dificultad del plan
     */
    private $difficulty;

    /**
     * @var string Duración estimada del plan
     */
    private $duration;

    /**
     * @var float Precio del plan
     */
    private $price;

    /**
     * @var string URL de la imagen del plan
     */
    private $image_guid;

    /**
     * @var string Ruta del archivo PDF asociado al plan
     */
    private $pdf_path;

    /**
     * @var string Fecha de creación del plan
     */
    private $created_at;

    /**
     * @var bool Variable que controla si esta activo o no el producto
     */
    private $is_active;

    /**
     * Constructor
     */
    public function __construct($id, $trainer_id, $name, $description, $difficulty, $duration, $price, $image_guid, $pdf_path, $created_at, $is_active)
    {
        $this->id = $id;
        $this->trainer_id = $trainer_id;
        $this->name = $name;
        $this->description = $description;
        $this->difficulty = $difficulty;
        $this->duration = $duration;
        $this->price = $price;
        $this->image_guid = $image_guid;
        $this->pdf_path = $pdf_path;
        $this->created_at = $created_at;
        $this->is_active = $is_active;
    }

    /**
     * Getters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getTrainerId()
    {
        return $this->trainer_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDifficulty()
    {
        return $this->difficulty;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getImageGuid()
    {
        return $this->image_guid;
    }

    public function getPdfPath()
    {
        return $this->pdf_path;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Setters
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTrainerId($trainer_id)
    {
        $this->trainer_id = $trainer_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setImageGuid($image_guid)
    {
        $this->image_guid = $image_guid;
    }

    public function setPdfPath($pdf_path)
    {
        $this->pdf_path = $pdf_path;
    }
   
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
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