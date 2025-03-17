<?php

namespace TheBalance\user;

/**
 * Mock de usuario
 */
class userMock implements IUser
{

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Realiza el inicio de sesión de un usuario
     * 
     * @param userDTO $userDTO Datos del usuario
     * @return userDTO Usuario encontrado
     */
    public function login($userDTO)
    {
        // Simula que el usuario existe y devuelve un userDTO ficticio
        return new userDTO(1, "perspna@ucm.es", "1234", 0);
    }

    /**
     * Crea un usuario
     * 
     * @param userDTO $userDTO Datos del usuario
     * @return userDTO Usuario creado
     */
    public function create($userDTO)
    {
        // Simula que el usuario se ha registrado y devuelve un userDTO con un ID ficticio
        return new userDTO(2, $userDTO->email(), $userDTO->password(), $userDTO->usertype());
    }
}
