<?php

namespace TheBalance\plan;

class planPurchaseDTO implements \JsonSerializable
{

    /**
     * @var int Identificador de la compra del plan
     */
    private $id;

    /**
     * @var int Identificador del plan
     */
    private $plan_id;

    /**
     * @var int Identificador del cliente
     */
    private $client_id;

    /**
     * @var Date Fecha de creacion
     */
    private $purchase_date;

    /**
     * @var string estado del plan
     */    
    private $status;

    public function __construct($id, $plan_id, $client_id, $purchase_date, $status)
    {
        $this->id = $id;
        $this->plan_id = $plan_id;
        $this->client_id = $client_id;
        $this->purchase_date = $purchase_date;
        $this->status = $status;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getPlanId()
    {
        return $this->plan_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function getPurchaseDate()
    {
        return $this->purchase_date;
    }

    public function getStatus()
    {
        return $this->status;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setPlanId($plan_id)
    {
        $this->plan_id = $plan_id;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    public function setPurchaseDate($purchase_date)
    {
        $this->purchase_date = $purchase_date;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
