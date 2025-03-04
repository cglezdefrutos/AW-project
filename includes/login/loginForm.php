<?php

include __DIR__ . "/../views/common/baseForm.php";
include __DIR__ . "/../user/userAppService.php";

class loginForm extends baseForm
{
    public function __construct() 
    {
        parent::__construct('loginForm');
    }
    
    protected function CreateFields($data)
    {
        $userName = '';
        
        if ($data) 
        {
            $userName = isset($data['userName']) ? $data['userName'] : $userName;
        }

        $html = <<<EOF
        <fieldset>
            <legend>Usuario y contraseña</legend>
            <p><label>Nombre:</label> <input type="text" name="userName" value="$userName"/></p>
            <p><label>Password:</label> <input type="password" name="password" /></p>
            <button type="submit" name="login">Entrar</button>
        </fieldset>
EOF;
        return $html;
    }
    

    protected function Process($data)
    {
        $result = array();

        $userName = trim($data['userName'] ?? '');
        
        $userName = filter_var($userName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
        if ( empty($userName) ) 
        {
            $result[] = "El nombre de usuario no puede estar vacío";
        }
        
        $password = trim($data['password'] ?? '');
        
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ( empty($password) ) 
        {
            $result[] = "El password no puede estar vacío.";
        }
        
        if (count($result) === 0) 
        {
            $userDTO = new userDTO(0, $userName, $password, -1);

            $userAppService = userAppService::GetSingleton();

            $foundedUserDTO = $userAppService->login($userDTO);

            if ( ! $foundedUserDTO ) 
            {
                // No se da pistas a un posible atacante
                $result[] = "El usuario o el password no coinciden";
            } 
            else 
            {
                $_SESSION["login"] = true;
                $_SESSION["username"] = $foundedUserDTO->username();
                $_SESSION["usertype"] = $foundedUserDTO->type();

                $result = 'index.php';
            }
        }
        return $result;
    }
}

?>