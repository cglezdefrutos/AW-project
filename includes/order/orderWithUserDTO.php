<?php

namespace TheBalance\order;

class OrderWithUserDTO implements \JsonSerializable {
    /**
     * @var int Identificador del usuario
     */
    private $id;

    /**
     * @var int Id del usuario al que pertenece
     */
    private $user_id;

    /**
     * @var string email del usuario
     */
    private $email;

    /**
     * @var int address_id al que pertenece
     */
    private $address_id;

    /**
     * @var float address_id al que pertenece
     */
    private $total_price;

    /**
     * @var string estado actual del pedido
     */
    private $status;

    /**
     * @var Date Fecha de creacion
     */
    private $created_at;

    /**
     * Constructor
     */
    public function __construct($id, $userId, $email, $addressId, $totalPrice, $status, $createdAt) {
        $this->id = $id;
        $this->userId = $userId;
        $this->email = $email;
        $this->addressId = $addressId;
        $this->totalPrice = $totalPrice;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    /**
     * Getters
     */
    public function getId() { return $this->id; }
    public function getUserId() { return $this->userId; }
    public function getEmail() { return $this->email; }
    public function getAddressId() { return $this->addressId; }
    public function getTotalPrice() { return $this->totalPrice; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * Implementación de JsonSerializable
     */
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
