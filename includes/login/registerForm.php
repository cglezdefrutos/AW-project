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
        $userName = '';
        $userType = '0'; // Valor predeterminado (Cliente)
        
        if ($data) 
        {
            $userName = isset($data['userName']) ? $data['userName'] : $userName;
            $userType = isset($data['userType']) ? $data['userType'] : $userType;
        }

        $html = <<<EOF
        <fieldset>
            <legend>Inserta los datos</legend>
            <p><label>Nombre:</label> <input type="text" name="userName" value="$userName"/></p>
            <p><label>Password:</label> <input type="password" name="password" /></p>
            <p><label>Re-Password:</label> <input type="password" name="rePassword" /></p>
            
            <legend>Selecciona tu tipo de usuario:</legend>
            <p>
                <input type="radio" name="userType" value="0" id="client" 
                ".($userType === '0' ? 'checked' : '')."> 
                <label for="client">Cliente</label>

                <input type="radio" name="userType" value="1" id="supplier" 
                ".($userType === '1' ? 'checked' : '')."> 
                <label for="supplier">Proveedor</label>

                <input type="radio" name="userType" value="2" id="trainer" 
                ".($userType === '2' ? 'checked' : '')."> 
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
        
        $userName = trim($data['userName'] ?? '');
        $userName = filter_var($userName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($userName)) 
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

        $userType = $data['userType'] ?? null;

        if ($userType === null) 
        {
            $result[] = "Debes seleccionar un tipo de usuario.";
        }

        if (count($result) === 0) 
        {
            try
            {
                $userDTO = new userDTO(0, $userName, $password, (int)$userType);

                $userAppService = userAppService::GetSingleton();

                $createdUserDTO = $userAppService->create($userDTO);

                $_SESSION["login"] = true;
                $_SESSION["username"] = $userName;
                $_SESSION["usertype"] = $userType;

                $result = 'index.php';

                $app = application::getInstance();
                
                $message = "Se ha registrado exitosamente como " . 
                            ($userType == 0 ? "Cliente" : ($userType == 1 ? "Proveedor" : "Entrenador")) . 
                            ", Bienvenido {$userName}!";
                
                $app->putAtributoPeticion('mensaje', $message);
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
