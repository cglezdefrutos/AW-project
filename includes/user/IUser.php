<?php

namespace TheBalance\user;

/**
 * Interfaz para el acceso a datos de usuario
 */
interface IUser
{
    /**
     * Inicia sesión
     * 
     * @param userDTO $userDTO
     * @return userDTO
     */
    public function login($userDTO);

    /**
     * Crea un usuario
     * 
     * @param userDTO $userDTO
     * @return userDTO
     */
    public function create($userDTO);
}