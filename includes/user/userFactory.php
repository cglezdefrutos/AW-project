<?php

namespace TheBalance\user;

/**
 * Factoría de usuarios
 */
class userFactory
{
    /**
     * Crea un DAO de usuario
     * 
     * @return IUser DAO de Usuario creado
     */
    public static function CreateUser() : IUser
    {
        $userDAO = false;
        $config = "DAO";

        if ($config === "DAO")
        {
            $userDAO = new userDAO();
        }
        else
        {
            $userDAO = new userMock();
        }
        
        return $userDAO;
    }
}
?>