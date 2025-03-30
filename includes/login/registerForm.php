<?php

namespace TheBalance\login;

use TheBalance\views\common\baseForm;
use TheBalance\user\userAppService;
use TheBalance\user\userDTO;
use TheBalance\application;

/**
 * Formulario de registro
 */
class registerForm extends baseForm
{
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct('registerForm');
    }
    
    /**
     * Crea los campos del formulario
     * 
     * @param array $data Datos del formulario
     * 
     * @return string HTML
     */
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
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Inserta los datos</legend>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Introduce tu email" value="$email" required>
                    <div class="invalid-feedback">Por favor, introduce un email válido.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Introduce tu contraseña" required>
                    <div class="invalid-feedback">Por favor, introduce tu contraseña.</div>
                </div>

                <div class="mb-3">
                    <label for="rePassword" class="form-label">Re-Password:</label>
                    <input type="password" name="rePassword" id="rePassword" class="form-control" placeholder="Repite tu contraseña" required>
                    <div class="invalid-feedback">Por favor, repite tu contraseña.</div>
                </div>

                <legend class="w-auto mt-2">Selecciona tu tipo de usuario:</legend>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="radio" name="usertype" value="1" id="client" class="form-check-input" 
                        ".($userType === '1' ? 'checked' : '').">
                        <label for="client" class="form-check-label">Cliente</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="usertype" value="2" id="supplier" class="form-check-input" 
                        ".($userType === '2' ? 'checked' : '').">
                        <label for="supplier" class="form-check-label">Proveedor</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="usertype" value="3" id="trainer" class="form-check-input" 
                        ".($userType === '3' ? 'checked' : '').">
                        <label for="trainer" class="form-check-label">Entrenador</label>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" name="register" class="btn btn-primary w-100">Registrarse</button>
                </div>
            </fieldset>
        EOF;

        return $html;
    }
    
    /**
     * Procesa los datos del formulario
     * 
     * @param array $data Datos del formulario
     * 
     * @return array|string Errores de procesamiento|Redirección
     */
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

                $app = application::getInstance();
                $app->loginUser($userJSON);

                $result = 'index.php';

                if(true)
                { 
                    // Poner en false para no conectarse a la base de datos
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
