<?php

namespace TheBalance\login;

use TheBalance\views\common\baseForm;
use TheBalance\user\userAppService;
use TheBalance\user\userDTO;
use TheBalance\application;

/**
 * Formulario de inicio de sesión
 */
class loginForm extends baseForm
{
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct('loginForm');
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
        
        if ($data) 
        {
            $email = isset($data['email']) ? $data['email'] : $email;
        }

        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Usuario y contraseña</legend>

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
                <div class="mt-3">
                    <button type="submit" name="login" class="btn btn-primary w-100">Entrar</button>
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
                
        if ( empty($email) ) 
        {
            $result[] = "El email de usuario no puede estar vacío";
        }
        
        $password = trim($data['password'] ?? '');
        
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ( empty($password) ) 
        {
            $result[] = "El password no puede estar vacío.";
        }
        
        if (count($result) === 0) 
        {
            $userDTO = new userDTO(0, $email, $password, -1);

            $userAppService = userAppService::GetSingleton();

            $foundedUserDTO = $userAppService->login($userDTO);

            if ( ! $foundedUserDTO ) 
            {
                // No se da pistas a un posible atacante
                $result[] = "El usuario o el password no coinciden";
            } 
            else 
            {
                $userJSON = json_encode($foundedUserDTO);
                
                $app = application::getInstance();
                $app->loginUser($userJSON);

                $result = 'index.php';
            }
        }
        return $result;
    }
}