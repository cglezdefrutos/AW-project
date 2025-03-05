<?php

include __DIR__ . "/../views/common/baseForm.php";
include __DIR__ . "/../user/userAppService.php";

class registerForm extends baseForm
{
    public function __construct() 
    {
        parent::__construct('registerForm');
    }
    
    protected function CreateFields($data)
    {
        $email = '';
        $userType = '1'; // Valor predeterminado (Cliente)
        
        if ($data) 
        {
            $email = isset($data['email']) ? $data['email'] : $email;
            $userType = isset($data['usertype']) ? $data['usertype'] : $userType;
        }

        $html = <<<EOF
        <fieldset>
            <legend>Inserta los datos</legend>
            <p><label>Email:</label> <input type="email" name="email" value="$email"/></p>
            <p><label>Password:</label> <input type="password" name="password" /></p>
            <p><label>Re-Password:</label> <input type="password" name="rePassword" /></p>
            
            <legend>Selecciona tu tipo de usuario:</legend>
            <p>
                <input type="radio" name="usertype" value="1" id="client" 
                ".($userType === '1' ? 'checked' : '')."> 
                <label for="client">Cliente</label>

                <input type="radio" name="usertype" value="2" id="supplier" 
                ".($userType === '2' ? 'checked' : '')."> 
                <label for="supplier">Proveedor</label>

                <input type="radio" name="usertype" value="3" id="trainer" 
                ".($userType === '3' ? 'checked' : '')."> 
                <label for="trainer">Entrenador</label>
            </p>

            <button type="submit" name="register">Registrarse</button>
        </fieldset>
EOF;
        return $html;
    }
    
    protected function Process($data)
    {
        $result = array();
        
        $email = trim($data['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($email)) 
        {
            $result[] = "El nombre de usuario no puede estar vacío.";
        }
        
        $password = trim($data['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($password)) 
        {
            $result[] = "El password no puede estar vacío.";
        }

        $rePassword = trim($data['rePassword'] ?? '');
        $rePassword = filter_var($rePassword, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($password !== $rePassword)
        {
            $result[] = "El password no coincide.";
        }

        $userType = $data['usertype'] ?? null;

        if ($userType === null) 
        {
            $result[] = "Debes seleccionar un tipo de usuario.";
        }

        if (count($result) === 0) 
        {
            try
            {
                $userDTO = new userDTO(0, $email, $password, (int)$userType);

                $userAppService = userAppService::GetSingleton();

                $createdUserDTO = $userAppService->create($userDTO);

                $userJSON = json_encode($createdUserDTO);
                $_SESSION["login"] = true;
                $_SESSION["user"] = $userJSON;

                /* $_SESSION["login"] = true;
                $_SESSION["email"] = $createdUserDTO->email();
                $_SESSION["usertype"] = $createdUserDTO->usertype(); */

                $result = 'index.php';


                if(true){ //poner en false para no conectarse a la base de datos
                    $app = application::getInstance();
                
                    $message = "Se ha registrado exitosamente como " . 
                                ($userType == 1 ? "Cliente" : ($userType == 2 ? "Proveedor" : "Entrenador")) . 
                                ", Bienvenido {$email}!";
                   
                    $app->putRequestAttribute('mensaje', $message);
                }

            }
            catch(userAlreadyExistException $e)
            {
                $result[] = $e->getMessage();
            }
        }

        return $result;
    }
}
?>
