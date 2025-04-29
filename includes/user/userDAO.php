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
     * Elimina un usuario
     * 
     * @param int $userId ID del usuario
     * 
     * @return bool Resultado de la operación
     */
    public function deleteUser($userId)
    {
        $result = false;

        $conn = application::getInstance()->getConnectionDb();

        if (!$conn) {
            throw new Exception("No se pudo establecer la conexión a la base de datos.");
        }

        $query = "DELETE FROM users WHERE id = ?";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $userId);

        if ($stmt->execute())
        {
            $result = true;
        }
        else
        {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        return $result;
    }

    /**
     * Devuelve todos los usuarios
     * 
     * @return array $usersDTO Lista de usuarios
     */
    public function getUsers()
    {
        $usersDTO = [];

        $conn = application::getInstance()->getConnectionDb();

        if (!$conn) {
            throw new Exception("No se pudo establecer la conexión a la base de datos.");
        }

        $query = "SELECT id, email, usertype FROM users";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }

        if ($stmt->execute())
        {
            $stmt->bind_result($id, $email, $usertype);

            while ($stmt->fetch())
            {
                $user = new userDTO($id, $email, '', $usertype);
                array_push($usersDTO, $user);
            }
        }
        else
        {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

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
        $result = false;

        // Verifica si las contraseñas coinciden
        if ($newPassword === $repeatNewPassword) 
        {
            $hashedPassword = self::hashPassword($newPassword);

            $conn = application::getInstance()->getConnectionDb();
            if (!$conn) {
                throw new Exception("No se pudo establecer la conexión a la base de datos.");
            }

            $query = "UPDATE users SET password = ? WHERE id = ?";

            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $conn->error);
            }

            $stmt->bind_param("si", $hashedPassword, $userId);

            if ($stmt->execute())
            {
                $result = true;
            }
            else
            {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }

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
        $email = false;

        $conn = application::getInstance()->getConnectionDb();

        if (!$conn) {
            throw new Exception("No se pudo establecer la conexión a la base de datos.");
        }

        $query = "SELECT email FROM users WHERE id = ?";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $userId);

        if ($stmt->execute())
        {
            $stmt->bind_result($email);
            if ($stmt->fetch())
            {
                return htmlspecialchars($email);
            }
        }
        else
        {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        return false;
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
        $result = false;

        $conn = application::getInstance()->getConnectionDb();

        if (!$conn) {
            throw new Exception("No se pudo establecer la conexión a la base de datos.");
        }

        $query = "UPDATE users SET email = ? WHERE id = ?";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }

        $escNewEmail = $this->realEscapeString($newEmail);
        $stmt->bind_param("si", $escNewEmail, $userId);

        if ($stmt->execute())
        {
            $result = true;
        }
        else
        {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

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
        $userType = false;

        $conn = application::getInstance()->getConnectionDb();

        if (!$conn) {
            throw new Exception("No se pudo establecer la conexión a la base de datos.");
        }

        $query = "SELECT usertype FROM users WHERE id = ?";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $userId);

        if ($stmt->execute())
        {
            $stmt->bind_result($userType);
            if ($stmt->fetch())
            {
                return htmlspecialchars($userType);
            }
        }
        else
        {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        return false;
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
        $result = false;

        $conn = application::getInstance()->getConnectionDb();

        if (!$conn) {
            throw new Exception("No se pudo establecer la conexión a la base de datos.");
        }

        $query = "UPDATE users SET usertype = ? WHERE id = ?";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }

        $stmt->bind_param("ii", $userType, $userId);

        if ($stmt->execute())
        {
            $result = true;
        }
        else
        {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        return $result;
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