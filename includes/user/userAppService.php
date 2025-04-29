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
     * Elimina un usuario
     * 
     * @param int $userId ID del usuario
     * 
     * @return bool Resultado de la operación
     */
    public function deleteUser($userId)
    {
        $IUserDAO = userFactory::CreateUser();

        $result = $IUserDAO->deleteUser($userId);

        return $result;
    }

    /**
     * Devuelve todos los usuarios
     * 
     * @return array $usersDTO Lista de usuarios
     */
    public function getUsers()
    {
        $IUserDAO = userFactory::CreateUser();

        $usersDTO = $IUserDAO->getUsers();

        return $usersDTO;
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
     * Devuelve el email de un usuario
     * 
     * @param int $userId ID del usuario
     * 
     * @return string $email Email del usuario
     */
    public function getEmail($userId)
    {
        $IUserDAO = userFactory::CreateUser();

        $email = $IUserDAO->getEmail($userId);

        return $email;
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

    /**
     * Devuelve el tipo de usuario de un usuario
     * 
     * @param int $userId ID del usuario
     * 
     * @return string $userType Tipo de usuario
     */
    public function getUserType($userId)
    {
        $IUserDAO = userFactory::CreateUser();

        $userType = $IUserDAO->getUserType($userId);

        return $userType;
    }

    /**
     * Actualiza el tipo de usuario
     * 
     * @param int $userId ID del usuario
     * @param int $userType Nuevo tipo de usuario
     * 
     * @return bool Resultado de la operación
     */
    public function changeUserType($userId, $userType)
    {
        $IUserDAO = userFactory::CreateUser();

        $result = $IUserDAO->changeUserType($userId, $userType);

        return $result;
    }
}