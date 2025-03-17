<?php

namespace TheBalance\user;

/**
 * Clase que contiene la lógica de la aplicación de usuarios
 */
class userAppService
{
    // Patrón Singleton
    /**
     * @var userAppService Instancia de la clase
     */
    private static $instance;

    /**
     * Devuelve una instancia de {@see userAppService}.
     * 
     * @return userAppService Obtiene la única instancia de la <code>userAppService</code>
     */
    public static function GetSingleton()
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Evita que se pueda instanciar la clase directamente.
     */
    private function __construct()
    {
    } 

    /**
     * Inicio de sesión de un usuario
     * 
     * @param array $userData Datos del usuario
     * 
     * @return userDTO $foundedUserDTO Usuario encontrado
     */
    public function login($userDTO)
    {
        $IUserDAO = userFactory::CreateUser();

        $foundedUserDTO = $IUserDAO->login($userDTO);

        return $foundedUserDTO;
    }

    /**
     * Creación de un usuario
     * 
     * @param array $userDTO Datos del usuario
     * 
     * @return userDTO $createdUserDTO Usuario creado
     */
    public function create($userDTO)
    {
        $IUserDAO = userFactory::CreateUser();

        $createdUserDTO = $IUserDAO->create($userDTO);

        return $createdUserDTO;
    }
}