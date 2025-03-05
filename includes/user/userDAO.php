<?php

require("IUser.php");
require("userDTO.php");
require(__DIR__ . "/../views/common/baseDAO.php");
require("userAlreadyExistException.php");

class userDAO extends baseDAO implements IUser
{
    public function __construct()
    {

    }

    public function login($userDTO)
    {
        $foundedUserDTO = $this->searchUser($userDTO->email());
        
        if ($foundedUserDTO && self::testHashPassword( $userDTO->password(), $foundedUserDTO->password())) 
        {
            return $foundedUserDTO;
        } 

        return false;
    }

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

    public function create($userDTO)
    {
        $createdUserDTO = false;

        try
        {
            $escemail = $this->realEscapeString($userDTO->email());

            $hashedPassword = self::hashPassword($userDTO->password());

            $userType = (int)$userDTO->usertype();

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

    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private static function testHashPassword($password, $hashedPassword)
    {
        var_dump($password);
        var_dump($hashedPassword);
        
        $result = password_verify($password, $hashedPassword);
        var_dump($result);
        return $result;
    }
}
?>