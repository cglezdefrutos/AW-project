<?php

require_once("IUser.php");
require_once("userDTO.php");

class userMock implements IUser
{

    public function __construct()
    {

    }

    public function login($userDTO)
    {
        // Simula que el usuario existe y devuelve un userDTO ficticio
        return new userDTO(1, "e@e.es", "Feo", 0); // 0 = Cliente
    }

    public function create($userDTO)
    {
        // Simula que el usuario se ha registrado y devuelve un userDTO con un ID ficticio
        return new userDTO(2, $userDTO->email(), $userDTO->password(), $userDTO->usertype());
    }
}

?>
