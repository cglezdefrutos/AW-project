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

    /**
     * Cambia la contraseña de un usuario
     * 
     * @param int $userId ID del usuario
     * @param string $newPassword Nueva contraseña
     * @param string $repeatNewPassword Repetir nueva contraseña
     * 
     * @return bool Resultado de la operación
     */
    public function changePassword($userId, $newPassword, $repeatNewPassword)
    {
        $IUserDAO = userFactory::CreateUser();
        
        $result = $IUserDAO->changePassword($userId, $newPassword, $repeatNewPassword);

        return $result;
    }

    /**
     * Cambia el email de un usuario
     * 
     * @param int $userId ID del usuario
     * @param string $newEmail Nuevo email
     * 
     * @return array Resultado de la operación
     */
    public function changeEmail($userId, $newEmail)
    {
        $IUserDAO = userFactory::CreateUser();

        $result = $IUserDAO->changeEmail($userId, $newEmail);

        return $result;
    }
}