<?php

namespace TheBalance\user;

use TheBalance\views\common\baseDAO;
use TheBalance\application;

/**
 * Clase que contiene la lógica de acceso a datos de usuarios
 */
class userDAO extends baseDAO implements IUser
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Inicio de sesión de un usuario
     * 
     * @param userDTO $userDTO Datos del usuario
     * 
     * @return userDTO|bool Usuario encontrado|false
     */
    public function login($userDTO)
    {
        $foundedUserDTO = $this->searchUser($this->realEscapeString($userDTO->email()));
        
        if ($foundedUserDTO && self::testHashPassword( $this->realEscapeString($userDTO->password()), $foundedUserDTO->password())) 
        {
            return $foundedUserDTO;
        } 

        return false;
    }

    /**
     * Busca un usuario por su email
     * 
     * @param string $email Email del usuario
     * 
     * @return userDTO|bool Usuario encontrado|false
     */
    private function searchUser($email)
    {
        $escemail = $this->realEscapeString($email);

        $conn = application::getInstance()->getConnectionDb();

        if (!$conn) {
            throw new Exception("No se pudo establecer la conexión a la base de datos.");
        }

        $query = "SELECT id, email, password, usertype FROM users WHERE email = ?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("s", $escemail);

        $stmt->execute();

        $stmt->bind_result($id, $email, $password, $usertype);

        if ($stmt->fetch())
        {
            $user = new userDTO($id, $email, $password, $usertype);

            $stmt->close();

            return $user;
        }

        return false;
    }

    /**
     * Creación de un usuario
     * 
     * @param userDTO $userDTO Datos del usuario
     * 
     * @return userDTO Usuario creado
     */
    public function create($userDTO)
    {
        $createdUserDTO = false;

        try
        {
            $escemail = $this->realEscapeString($userDTO->email());

            $hashedPassword = self::hashPassword($userDTO->password());

            $userType = $this->realEscapeString((int)$userDTO->usertype());

            $conn = application::getInstance()->getConnectionDb();

            if (!$conn) {
                throw new Exception("No se pudo establecer la conexión a la base de datos.");
            }

            $query = "INSERT INTO users(email, password, usertype) VALUES (?, ?, ?)";

            $stmt = $conn->prepare($query);

            $stmt->bind_param("ssi", $escemail, $hashedPassword, $userType);

            if ($stmt->execute())
            {
                $idUser = $conn->insert_id;
                
                $createdUserDTO = new userDTO($idUser, $escemail, $hashedPassword, $userType);

                return $createdUserDTO;
            }
        }
        catch(mysqli_sql_exception $e)
        {
            // código de violación de restricción de integridad (PK)

            if ($conn->sqlstate == 23000) 
            { 
                throw new userAlreadyExistException("Ya existe el usuario '{$userDTO->email()}'");
            }

            throw $e;
        }

        return $createdUserDTO;
    }

    /**
     * Nos da el hash de la contraseña
     * 
     * @param string $password Contraseña
     * 
     * @return string Hash de la contraseña
     */
    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Comprueba si la contraseña es correcta
     * 
     * @param string $password Contraseña
     * @param string $hashedPassword Hash de la contraseña
     * 
     * @return bool Resultado de la comprobación
     */
    private static function testHashPassword($password, $hashedPassword)
    {        
        $result = password_verify($password, $hashedPassword);
        return $result;
    }
}