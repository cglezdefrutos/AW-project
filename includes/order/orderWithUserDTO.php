<?php

namespace TheBalance\order;

class orderWithUserDTO implements \JsonSerializable {
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
     * @var float address_id al que pertenece
     */
    private $total_price;

    /**
     * @var string estado actual del pedido
     */
    private $status;

    /**
     * @var string direccion de envio al que pertenece
     */
    private $shipping_address;

    /**
     * @var Date Fecha de creacion
     */
    private $created_at;

    /**
     * Constructor
     */
    public function __construct($id, $userId, $email, $totalPrice, $status, $shipping_address, $createdAt) {
        $this->id = $id;
        $this->userId = $userId;
        $this->email = $email;
        $this->totalPrice = $totalPrice;
        $this->status = $status;
        $this->shipping_address = $shipping_address;
        $this->createdAt = $createdAt;
    }

    /**
     * Getters
     */
    public function getId() { return $this->id; }
    public function getUserId() { return $this->userId; }
    public function getEmail() { return $this->email; }
    public function getTotalPrice() { return $this->totalPrice; }
    public function getStatus() { return $this->status; }
    public function getShippingAddress() { return $this->shipping_address; }
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * Implementación de JsonSerializable
     */
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
